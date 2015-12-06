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
                            <div ng-if="lookupComplete" role="tabpanel" class="tab-pane active" id="restaurants" ng-cloak>
                                <div class="panel-body" ng-show="restaurants.length == 0">There are no restaurants assigned to your account.</div>
                                <table ng-show="restaurants.length > 0" class="table table-striped table-hover table-bordered dashboard-table">
                                    <tr>
                                        <th class="col-md-6">Restaurant Details</th>
                                        <th class="col-md-2">Total Menu Items</th>
                                        <th class="col-md-2">Advertised Menu Items</th>
                                        <th class="col-md-2">Maximum Number of<br />Advertised Menu Items</th>
                                    </tr>
                                    <tr ng-repeat="restaurant in restaurants | startFrom:(currentPage-1)*itemsPerPage | limitTo:itemsPerPage">
                                        <td>@{{restaurant.name}}<br />@{{restaurant.address1}} @{{restaurant.address2}}<br />
                                            @{{restaurant.city}}, @{{restaurant.state}} @{{restaurant.zipcode}} @{{restaurant.country}}<br />
                                            @{{restaurant.phone}}
                                        </td>
                                        <td><a href="javscript:void(0);" ng-click="showMenuItems(restaurant.id, restaurant.name, restaurant.max_menu_items)"><span ng-show="!menu_item_count['Restaurant'][restaurant.id]['total']">0</span><span ng-show="menu_item_count['Restaurant'][restaurant.id]['total']">@{{menu_item_count['Restaurant'][restaurant.id]['total']}}</span></a></td>
                                        <td><a href="javascript:void(0);" ng-click="showMenuItems(restaurant.id, restaurant.name, restaurant.max_menu_items)"><span ng-show="!menu_item_count['Restaurant'][restaurant.id]['active']">0</span><span ng-show="menu_item_count['Restaurant'][restaurant.id]['active']">@{{menu_item_count['Restaurant'][restaurant.id]['active']}}</span></a></td>
                                        <td><a href="/restaurant/manage/adslots?restaurant_id=@{{restaurant.id}}">@{{restaurant.max_menu_items}}</a></td>
                                    </tr>
                                </table>
                                <div style="width: 100%;" ng-show="restaurants.length > 0">
                                    <div style="float: left; margin: 20px 0px;"><pre>Page: @{{currentPage}} / @{{numPages}}</pre></div>
                                    <uib-pagination style="float: right; " items-per-page="itemsPerPage" boundary-links="true" total-items="totalItems" num-pages = "numPages" ng-model="$parent.currentPage" class="pagination-sm" previous-text="&lsaquo;" next-text="&rsaquo;" first-text="&laquo;" last-text="&raquo;"></uib-pagination>
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
                                    <th class="col-md-4">Menu Item</th>
                                    <th class="col-md-5">Availability</th>
                                    <th class="col-md-3">Advertised (@{{num_active}} / @{{max_menu_items}})</th>
                                </tr>
                                <tr ng-repeat="(index, menu_item) in menu_items | startFrom:(currentPage-1)*itemsPerPage | limitTo:itemsPerPage">
                                    <td class="col-md-4"><a href="javascript:void(0);" ng-click="openMenuItem(menu_item.id, false)">@{{menu_item.name}}</td>
                                    <td class="col-md-5"><a href="javascript:void(0);" ng-click="openMenuItem(menu_item.id, true)">
                                        <span ng-show="menu_item.availability['days'].length > 0 && menu_item.availability['days'].length < 7" ng-repeat="day in menu_item.availability['days']"><span ng-show="$last && menu_item.availability['days'].length>1">and </span>@{{days_map[day]}}<span ng-show="!$last">,</span> </span><span ng-show="menu_item.availability['days'].length > 0 && menu_item.availability['days'].length < 7">Only</span><br />
                                        <span ng-repeat="course in menu_item.availability['courses']"><span ng-show="$last && menu_item.availability['courses'].length>1">and </span>@{{courses_map[course]}}<span ng-show="!$last">, </span></span>
                                        </a>
                                    </td>
                                    <td class="col-md-3"><input type="radio" name="active_@{{index}}" ng-model="menu_item.active" ng-checked="menu_item.active" ng-click="updateActive(index, true, $event)" ng-value="true">Yes&nbsp;&nbsp;
                                        <input type="radio" name="active_@{{index}}" ng-model="menu_item.active" ng-checked="!menu_item.active" ng-click="updateActive(index, false, $event)" ng-value="false">No
                                    </td>
                                </tr>
                            </table>
                            <div style="width: 100%;" ng-show="menu_items.length > 0">
                                <div style="float: left; margin: 20px 0px;"><pre>Page: @{{currentPage}} / @{{numPages}}</pre></div>
                                <uib-pagination style="float: right; " items-per-page="itemsPerPage" boundary-links="true" total-items="totalItems" num-pages = "numPages" ng-model="$parent.currentPage" class="pagination-sm" previous-text="&lsaquo;" next-text="&rsaquo;" first-text="&laquo;" last-text="&raquo;"></uib-pagination>
                            </div>
                        </div>
                        <div class="modal-footer">
                          <div style="display: inline-block;"><center><spinner name="spinner" img-src="/images/spinner.gif"></spinner></center></div>
                          <div style="display: inline-block;"><button class="btn btn-warning" type="button" ng-click="cancel()">Close</button></div>
                        </div>
                    </script>
                </div>
			</div><!-- panel -->

		</div><!-- col-md-10 -->

	</div><!-- row -->
@endauth
@endsection