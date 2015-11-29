'use strict';

var appCombined = angular.module('RFMenuItemCombinedModule', ['MenuItemModule', 'RestaurantFranchiseManageModule'])
.service("MenuItemService", [function() {
    this.menu_item = {menu_item_id: null, opened: false};
    this.openModal = function() {
      this.menu_item.opened = true;
    }
    this.getOpened = function() {
      return this.menu_item.opened;
    }
    this.setMenuItemId = function(menu_item_id) {
      this.menu_item.opened = false;
      this.menu_item.menu_item_id = menu_item_id;
    }
    this.getMenuItemId = function() {
      return this.menu_item.menu_item_id;
    }
    return this;
}]);