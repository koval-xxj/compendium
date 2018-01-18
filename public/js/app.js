//AUTOCOMPLITE

Vue.component('product-link', {
  template: '#product_link',
  props: {
    oData: Object
  }
});

var navigate = new Vue({
  el: "#autocomplete",
  data: {
    search: "",
    products: {},
    selected: ''
  },
  methods: {
    searh_product: function(pName){
      this.$http.post('/router.php', {
        action: 'search',
        params: {product_name: pName}
      }).then(function (response) {
        this.products = response.body;
      }, function (error) {
        console.log(error);
      });
    }
  },
  watch: {
    search: function(val) {
      if (val.length >= 2) {
        this.searh_product(val);
      }
    }
  }
});

//CATEGORIES TREE

var three = {
  name: 'ATC-классификация',
  base: true,
  children: []
};

Vue.component('item', {
  template: '#item-groups',
  props: {
    model: Object,
    dataType: null
  },
  data: function () {
    return {
      open: this.model.base ? true : false,
      type: null
    }
  },
  computed: {
    isFolder: function () {
      return this.model.children && this.model.children.length
    }
  },
  created: function () {
    if (this.model.base) {
      this.get_data('get_groups_list', 0);
    }
  },
  methods: {
    toggle: function () {
      if (this.model.base) {
        return;
      }
      console.log(this.dataType);
      if (this.isFolder) {
        this.open = !this.open;
      } else if (this.model.children > 0) {
        this.get_data('get_groups_list', this.model.id);
      } else if (this.model.children < 1) {
        this.get_data('get_products_list', this.model.id);
      }
    },
    get_data: function (sAction, iParID) {

      var oParms = {};

      switch (sAction) {
        case 'get_groups_list':
          oParms.parent_id = iParID;
          this.type = 'group';
          break;
        case 'get_products_list':
          oParms.group_id = iParID;
          this.type = 'product';
          break;
      }

      this.$http.post('/router.php', {
        action: sAction,
        params: oParms
      }).then(function (response) {
        this.model.children = response.body;
        if (!this.model.base) {
          this.open = !this.open;
        }
      }, function (error) {
        console.log(error);
      });

    }
  }
})

var group = new Vue({
  el: '#groups_list',
  data: {
    treeData: three
  }
});

//CATEGORIES TREE
