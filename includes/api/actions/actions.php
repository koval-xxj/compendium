<?php
/**
 * Created by PhpStorm.
 * User: koval
 */

return [
  'Groups' => [
    'get_groups_list' => [
      'method' => 'getList',
      'params' => ['parent_id']
    ]
  ],
  'Products' => [
    'get_products_list' => [
      'method' => 'getList',
      'params' => ['group_id']
    ],
    'search' => [
      'method' => 'searchProduct',
      'params' => ['product_name']
    ],
    'fetch' => [
      'method' => 'getProduct',
      'params' => ['product_id']
    ]
  ]
];