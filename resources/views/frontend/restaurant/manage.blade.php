@extends('frontend.layouts.master')
@section('content')
@permission('view_restaurants')
	<div class="row">

		<div class="col-md-10 col-md-offset-1">

			<div class="panel panel-default" ng-controller="RestaurantFranchiseManageController">
                <div ng-show="landing">
    				<div class="panel-heading">My Restaurants</div>

    				<div class="panel-body">
    					<div role="tabpanel">

                          <!-- Nav tabs -->
                          <ul class="nav nav-tabs" role="tablist">
                            <li role="presentation" class="active"><a href="#restaurants" aria-controls="restaurants" role="tab" data-toggle="tab">Restaurants</a></li>
                            <li role="presentation"><a href="#franchises" aria-controls="franchises" role="tab" data-toggle="tab">Franchises</a></li>
                          </ul>

                          <div class="tab-content">
                            <div ng-show="lookupComplete" role="tabpanel" class="tab-pane active" id="restaurants" ng-cloak>
                                <div class="panel-body" ng-show="restaurants.length == 0">There are no restaurants assigned to your account.</div>
                                <table ng-if="restaurants.length > 0" class="table table-striped table-hover table-bordered dashboard-table">
                                    <tr>
                                        <th class="col-md-6">Restaurant Details</th>
                                        <th class="col-md-2">Total Menu Items</th>
                                        <th class="col-md-2">Advertised Menu Items</th>
                                        <th class="col-md-2">Maximum Number of<br />Advertised Menu Items</th>
                                    </tr>
                                    <tr ng-repeat="restaurant in restaurants | startFrom:currentPage*pageSize | limitTo:pageSize">
                                        <td>@{{restaurant.name}}<br />@{{restaurant.address1}} @{{restaurant.address2}}<br />
                                            @{{restaurant.city}}, @{{restaurant.state}} @{{restaurant.zipcode}} @{{restaurant.country}}<br />
                                            @{{restaurant.phone}}
                                        </td>
                                        <td><span ng-show="!menu_item_count['Restaurant'][restaurant.id]['total']">0</span><a ng-show="menu_item_count['Restaurant'][restaurant.id]['total']" href="javscript:void(0);" ng-click="showMenuItems(restaurant.id, restaurant.name, restaurant.max_menu_items)">@{{menu_item_count['Restaurant'][restaurant.id]['total']}}</a></td>
                                        <td><span ng-show="!menu_item_count['Restaurant'][restaurant.id]['active']">0</span><a ng-show="menu_item_count['Restaurant'][restaurant.id]['active']" href="javascript:void(0);" ng-click="showMenuItems(restaurant.id, restaurant.name, restaurant.max_menu_items)">@{{menu_item_count['Restaurant'][restaurant.id]['active']}}</a></td>
                                        <td><a href="/restaurant/manage/adslots?restaurant_id=@{{restaurant.id}}">@{{restaurant.max_menu_items}}</a></td>
                                    </tr>
                                </table>
                                <div style="float: right;" ng-if="restaurants.length > 0">
                                    <button ng-disabled="currentPage == 0" ng-hide="currentPage == 0" ng-click="currentPage=currentPage-1">Previous</button>
                                    Page: @{{currentPage+1}} / @{{numberOfPages()}}
                                    <button ng-disabled="currentPage >= restaurants.length/pageSize - 1" ng-hide="currentPage >= restaurants.length/pageSize - 1" ng-click="currentPage=currentPage+1">Next</button>
                                </div>
                            </div><!--tab panel profile-->

                            <div ng-show="lookupComplete" role="tabpanel" class="tab-pane" id="franchises" ng-cloak>
                                <div class="panel-body" ng-show="franchises.length == 0">There are no franchises assigned to your account.</div>
                                <table ng-show="franchises.length > 0" class="table table-striped table-hover table-bordered dashboard-table">
                                    <tr>
                                        <th>Franchise</th>
                                        <th># Properties</th>
                                    </tr>
                                    <tr ng-repeat="franchise in franchises">
                                        <td>@{{franchise.franchise_name}}</td>
                                        <td><a href="/restaurant/manage/franchise?id=@{{franchise.franchise_id}}">@{{franchise.restaurants.length}}</td>
                                    </tr>
                                </table>
                            </div><!--tab panel profile-->

                          </div><!--tab content-->

                        </div><!--tab panel-->

    				</div><!--panel body-->
                </div>
                <div id="menuItemsModal" ng-controller="MenuItemsInitCtrl">
                    <script type="text/ng-template" id="menuItems.html">
                        <div class="modal-header">
                            <h3 ng-show="lookupComplete" class="modal-title">Menu Items for @{{restaurant_name}}</h3>
                        </div>
                        <div class="modal-body" ng-show="lookupComplete" style="display: inline-table; width: 100%">
                            <table width="100%">
                                <tr>
                                    <th class="col-md-5">Menu Item</th>
                                    <th class="col-md-5">Advertisement Window</th>
                                    <th class="col-md-2">Active (@{{num_active}} / @{{max_menu_items}})</th>
                                </tr>
                                <tr ng-repeat="(index, menu_item) in menu_items">
                                    <td class="col-md-5"><a href="javascript:void(0);" ng-click="openMenuItem(menu_item.id)">@{{menu_item.name}}</td>
                                    <td class="col-md-5">@{{menu_item.name}}</td>
                                    <td class="col-md-2"><input type="radio" name="active_@{{index}}" ng-model="menu_item.active" ng-checked="menu_item.active" ng-click="updateActive(index, true, $event)" ng-value="true">Yes&nbsp;&nbsp;
                                        <input type="radio" name="active_@{{index}}" ng-model="menu_item.active" ng-checked="!menu_item.active" ng-click="updateActive(index, false, $event)" ng-value="false">No
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="modal-footer">
                          <center><spinner name="spinner" img-src="/images/spinner.gif"></spinner></center>
                          <button class="btn btn-warning" type="button" ng-click="cancel()">Close</button>
                        </div>
                    </script>
                </div>
			</div><!-- panel -->

		</div><!-- col-md-10 -->

	</div><!-- row -->
@endauth
@endsection