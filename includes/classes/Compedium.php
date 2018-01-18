<?php

/**
 * Description of Compedium
 *
 * @author Sergey K.
 */
class Compedium {
    
    protected $url;
    
    public function __construct($sUrl) {
      $this->url = $sUrl;
    }
    
    public function __get($key) {
      $value = '';
      switch ($key) {
        case 'domain':
          $parts = parse_url($this->url);
          $value = $parts['scheme'] . '://' . $parts['host'];
          break;
      }
      return $value;
    }
    
    public function parceUrl($sUrl) {
      $grabber = new Network();
      $grabber->method = 'get';
      $grabber->url = (string) $sUrl;
      $response = $grabber->sendRequest();
      
      if (!$response) {
        throw new Exception("Wrong URL or data: " . $sUrl);
      } else if (isset($response['error'])) {
        throw new Exception($response['error'] . ' url: ' . $sUrl);
      }
      
      return $response;
    }
    
    public function parsePage($sUrl = null, $iParrentId = null) {
      
      $sUrl = !$sUrl ? $this->url : $sUrl;
      try {
        $response = $this->parceUrl($sUrl);
      } catch (Exception $ex) {
        show_error($ex->getMessage());
        return;
      }
      
      $dom = str_get_html($response);
      
      if ( $dom->innertext != '') {
        foreach ($dom->find('ul.list-unstyled') as $ulDom) {
          $this->parseLinks($ulDom, $iParrentId);
        }
      }
      
    }
    
    public function parseLinks($dom, $iParrentId = null) {
      
      foreach($dom->find('a') as $a) {

        $oParrent = $a->parentNode();

        switch ($oParrent->class) {
          case 'branch':
            $iGroupID = $this->save_group_info($a, $iParrentId);
            $sUrl = $this->url . '?id=' . $a->getAttribute('data-id');
            $this->parsePage($sUrl, $iGroupID);
            break;
          case 'post':
            $this->save_product_info($a, $iParrentId);
            break;
        }
      }
      
      return $this;
    }
    
    protected function save_group_info($oElem, $iParentID = null) {
      
      $bElem = $oElem->find('b', 0);
      
      $aData = [
        'name' => (string) trim(strip_tags(str_replace($bElem->outertext, '', $oElem->outertext))),
        'atc_id' => (string) $oElem->getAttribute('id'),
        'code' => (int) $oElem->getAttribute('data-id')
      ];
      
      if ($iParentID) {
        $aData['parent_id'] = $iParentID;
      }
      
      $iGroupID = db_insert('groups', $aData);

      return $iGroupID;

    }
    
    protected function save_product_info($oElem, $iGroupId) {
      
      $mElem = $oElem->find('sup', 0);
      $sProduct = !is_null($mElem) ? strip_tags(str_replace($mElem->outertext, '', $oElem->outertext)) : $oElem->plaintext;
      $sProduct = preg_replace('![^а-яА-ЯёЁa-zA-Z0-9 \-\:\;\.\,]!u','', html_entity_decode($sProduct));
      $aProduct = explode(',', $sProduct);
      
      $aData = [
        'name' => (string)trim($aProduct[0]),
        'url' => (string) $this->domain . $oElem->href,
        'group_id' => (int)$iGroupId
      ];
      
      if (isset($aProduct[1])) {
        $aData['manufacturer'] = (string) trim($aProduct[1]);
      }
      
      db_insert('products', $aData);
      
    }
  
}
