	<div>
		@permission('create_menu_items')
		<div id="menuItemModal" ng-controller="MenuItemInitCtrl">
		    <script type="text/ng-template" id="menuItemForm.html">
		        <div class="modal-header">
		            <h3 ng-show="!id" class="modal-title">Create a Menu Item</h3>
		            <h3 ng-show="id" class="modal-title">Edit Menu Item</h3>
		        </div>
		        <div ng-show="!menuItemAdded" class="modal-body" style="display: inline-table; width: 100%">
		        	<div ng-show="step==1" style="display: inline-table; width: 100%">
			        	<b>Name</b><br />
						<input name="name" type="text" maxlength="127" style="width: 100%;" ng-model="menu_item.item.name" /></td><br /><br />
						<b>Short Description</b><br />
						<input name="tagline" type="text" maxlength="255" style="width: 100%;" ng-model="menu_item.item.tagline" /><br /><br />
			        	<b>Main Ingredients</b>&nbsp;(Separate with commas)<br />
			        	<textarea name="main_ingredients" style="width: 100%;" rows="2" ng-model="menu_item.item.main_ingredients"></textarea><br /><br />
			        	<b>Price Range (Fill in either one if single price)</b><br />
			        	Minimum: <input name="min_price" type="text" maxlength="7" style="width: 80px;" ng-model="menu_item.prices.min_price" />&nbsp;
			        	Maximum: <input name="max_price" type="text" maxlength="7" style="width: 80px;" ng-model="menu_item.prices.max_price" /><br />
		        	</div>
		        	<div ng-show="step==2" style="display: inline-table; width: 100%">
		        		<b>Cuisines</b>&nbsp;(Select up to 3)<br />
		        		<div class="col-md-3" style="display: inline-table;">
			        		<span style="display: block;" ng-repeat="cuisine in cuisines1"><input type="checkbox" name="cuisines" ng-checked="cuisineSelected(cuisine.id)" ng-click="selectCuisine(cuisine.id, $event)" ng-value="cuisine.id" /> @{{cuisine.value}}</span>
			        	</div>
		        		<div class="col-md-3" style="display: inline-table;">
			        		<span style="display: block;" ng-repeat="cuisine in cuisines2"><input type="checkbox" name="cuisines" ng-checked="cuisineSelected(cuisine.id)" ng-click="selectCuisine(cuisine.id, $event)" ng-value="cuisine.id" /> @{{cuisine.value}}</span>
			        	</div>
		        		<div class="col-md-3" style="display: inline-table;">
			        		<span style="display: block;" ng-repeat="cuisine in cuisines3"><input type="checkbox" name="cuisines" ng-checked="cuisineSelected(cuisine.id)" ng-click="selectCuisine(cuisine.id, $event)" ng-value="cuisine.id" /> @{{cuisine.value}}</span>
			        	</div>
			        	<div class="col-md-3" style="display: inline-table;">
			        		<span style="display: block;" ng-repeat="cuisine in cuisines4"><input type="checkbox" name="cuisines" ng-checked="cuisineSelected(cuisine.id)" ng-click="selectCuisine(cuisine.id, $event)" ng-value="cuisine.id" /> @{{cuisine.value}}</span>
			        	</div>
		        	</div>
		        	<div ng-show="step==3" style="display: inline-table; width: 100%">
		        		<div class="col-md-3" style="display: inline-table;">
		        			<b>Organic</b><br />
			        		<span style="display: block;" ng-repeat="item in organic"><input type="radio" name="organic" ng-checked="organicSelected(item.id)" ng-click="selectOrganic(item.id)" ng-value="item.id" /> @{{item.value}}</span>
			        	</div>
		        		<div class="col-md-3" style="display: inline-table;">
		        			<b>Allergens</b><br />
			        		<span style="display: block;" ng-repeat="item in allergens"><input type="checkbox" name="allergens" ng-checked="allergenSelected(item.id)" ng-click="selectAllergen(item.id, $event)" ng-value="item.id" /> @{{item.value}}</span>
			        	</div>
		        		<div class="col-md-3" style="display: inline-table;">
		        			<b>Diets</b><br />
			        		<span style="display: block;" ng-repeat="item in diets"><input type="checkbox" name="diets" ng-checked="dietSelected(item.id)" ng-click="selectDiet(item.id, $event)" ng-value="item.id" /> @{{item.value}}</span>
			        	</div>
		        		<div class="col-md-3" style="display: inline-table;">
		        			<b>Spicy</b><br />
			        		<span style="display: block;" ng-repeat="item in spicy"><input type="radio" name="spicy" ng-checked="spicySelected(item.id)" ng-click="selectSpicy(item.id)" ng-value="item.id" /> @{{item.value}}</span>
			        	</div>
		        	</div>
		        	<div ng-show="step==4" style="display: inline-table; width: 100%">
	        			<b>Select Property</b><br />
    					<div role="tabpanel">
                          <!-- Nav tabs -->
                          <ul class="nav nav-tabs" role="tablist">
                            <li role="presentation" ng-class="{active: restaurantIsIndy()}"><a id="indy-tab" href="#restaurant-select" aria-controls="restaurant-select" role="tab" data-toggle="tab">Restaurants</a></li>
                            <li role="presentation" ng-repeat="(index, restaurant) in restaurants" ng-class="{active: restaurantBelongsToFranchise(index)}" ng-show="restaurant.franchise_id"><a id="@{{restaurant.franchise_id}}-tab" href="#franchise-select_@{{restaurant.franchise_id}}" aria-controls="franchise-select" role="tab" data-toggle="tab">@{{restaurant.franchise_name}}</a></li>
                          </ul>
                          <div class="tab-content">
                            <div role="tabpanel" ng-class="{active: restaurantIsIndy()}" class="tab-pane" id="restaurant-select">
        						<table ng-show="restaurants['data']['restaurants'].length" width="100%">
        							<tr ng-repeat="indyRestaurant in restaurants['data']['restaurants']">
        								<td class="col-sm-12"><input type="radio" name="indyProperty" ng-checked="restaurantSelected(null, indyRestaurant.id)" ng-click="selectRestaurant(null, indyRestaurant.id)" ng-value="indyRestaurant.id" />
        								@{{indyRestaurant.name}} (@{{indyRestaurant.address1}} @{{indyRestaurant.city}}, @{{indyRestaurant.state}} @{{indyRestaurant.zipcode}})</td>
        							</tr>
        						</table>
                            </div>
                            <div role="tabpanel" class="tab-pane" ng-repeat="(index, franchise) in restaurants" ng-class="{active: restaurantBelongsToFranchise(index)}" id="franchise-select_@{{franchise.franchise_id}}">
                            	<table ng-show="franchise['restaurants'].length" width="100%">
	    							<tr>
	    								<td class="col-sm-12"><input type="radio" name="franProperty" ng-checked="franchiseSelected(franchise.franchise_id)" ng-click="selectFranchise(franchise.franchise_id)" ng-value="franchise.franchise_id" />
	    								@{{franchise.franchise_name}} (Entire Franchise)</td>
	    							</tr>
        							<tr ng-repeat="franchiseRestaurant in franchise.restaurants">
        								<td class="col-sm-12"><input type="radio" name="franProperty" ng-checked="restaurantSelected(franchise.franchise_id, franchiseRestaurant.id)" ng-click="selectRestaurant(franchise.franchise_id, franchiseRestaurant.id)" ng-value="franchiseRestaurant.id" />
        								@{{franchiseRestaurant.name}} (@{{franchiseRestaurant.address1}} @{{franchiseRestaurant.city}}, @{{franchiseRestaurant.state}} @{{franchiseRestaurant.zipcode}})</td>
        							</tr>
			        			</table>
                            </div>
                          </div>
                        </div>
		        	</div>
		        </div>
		        <div ng-show="menuItemAdded && !doSchedule && !menuItemsExceeded" class="modal-body" style="display: inline-table; width: 100%">
		        Your menu item has been <span ng-show="!id">created</span><span ng-show="id">updated</span>. Click <a href="javascript:void(0);" ng-click="schedule();">here</a> to schedule when this menu item will be advertised.
		        Otherwise, it&#39;ll always be advertised.
		        </div>
		        <div ng-show="menuItemAdded && !doSchedule && menuItemsExceeded" class="modal-body" style="display: inline-table; width: 100%">
		        Your menu item has been <span ng-show="!id">created</span><span ng-show="id">updated</span>, but it is inactive because you&#39;ve exceeded the number of menu items allowed for the restaurant you&#39;ve selected. Click <a href="/restaurant/manage/adslots?restaurant_id=@{{menu_item.restaurant_id}}&&franchise_id=@{{menu_item.franchise_id}}">here</a> to increase the number of menu items allowed for the restaurant.
		        </div>
		        <div ng-show="menuItemAdded && scheduled" class="modal-body" style="display: inline-table; width: 100%">
		        Your menu item has been scheduled.
		        </div>
	        	<div ng-show="menuItemAdded && doSchedule && !scheduled" class="modal-body" style="display: inline-table; width: 100%">
		        	<b>Select Availability Days and Meal Courses</b><br /><br />
		        	<span style="color: red;" ng-show="day_error">You must select at least one day.<br /></span>
		        	<span style="color: red;" ng-show="course_error">You must select at least one meal course.<br /></span>
			        <div style="display: inline-table; width: 100%">
			        	<div class="col-md-1" style="display: inline;"><input name="day" type="checkbox" ng-checked="daySelected('1')" ng-click="selectDay($event, '1')" ng-value="1" />Mon</div>
			        	<div class="col-md-1" style="display: inline;"><input name="day" type="checkbox" ng-checked="daySelected('2')" ng-click="selectDay($event, '2')" ng-value="2" />Tue</div>
			        	<div class="col-md-1" style="display: inline;"><input name="day" type="checkbox" ng-checked="daySelected('3')" ng-click="selectDay($event, '3')" ng-value="3" />Wed</div>
			        	<div class="col-md-1" style="display: inline;"><input name="day" type="checkbox" ng-checked="daySelected('4')" ng-click="selectDay($event, '4')" ng-value="4" />Thu</div>
			        	<div class="col-md-1" style="display: inline;"><input name="day" type="checkbox" ng-checked="daySelected('5')" ng-click="selectDay($event, '5')" ng-value="5" />Fri</div>
			        	<div class="col-md-1" style="display: inline;"><input name="day" type="checkbox" ng-checked="daySelected('6')" ng-click="selectDay($event, '6')" ng-value="6" />Sat</div>
			        	<div class="col-md-1" style="display: inline;"><input name="day" type="checkbox" ng-checked="daySelected('7')" ng-click="selectDay($event, '7')" ng-value="7" />Sun</div>
			        </div>
			        <div style="display: inline-table; width: 100%">
			        	<div class="col-md-2" style="display: inline;"><input type="checkbox" name="mealtime" ng-checked="courseSelected('1')" ng-click="selectCourse($event, '1')" ng-value="1">Breakfast</div>
			        	<div class="col-md-2" style="display: inline;"><input type="checkbox" name="mealtime" ng-checked="courseSelected('2')" ng-click="selectCourse($event, '2')" ng-value="2">Brunch</div>
			        	<div class="col-md-2" style="display: inline;"><input type="checkbox" name="mealtime" ng-checked="courseSelected('3')" ng-click="selectCourse($event, '3')" ng-value="3">Lunch</div>
			        	<div class="col-md-2" style="display: inline;"><input type="checkbox" name="mealtime" ng-checked="courseSelected('4')" ng-click="selectCourse($event, '4')" ng-value="4">Tea</div>
			        	<div class="col-md-2" style="display: inline;"><input type="checkbox" name="mealtime" ng-checked="courseSelected('5')" ng-click="selectCourse($event, '5')" ng-value="5">Dinner</div>
			        	<div class="col-md-2" style="display: inline;"><input type="checkbox" name="mealtime" ng-checked="courseSelected('6')" ng-click="selectCourse($event, '6')" ng-value="6">Late-Night</div>
			        </div>
			        <div style="display: block; width: 100%">
			        	<div class="col-md-6" style="display: inline-block;">
			        	<b>Start Date</b><br />
			            <p class="input-group">
			              <input type="text" class="form-control" uib-datepicker-popup="@{{format}}" is-open="status.start_date.opened" ng-model="start_date_obj" datepicker-options="dateOptions" close-text="Close" />
			              <span class="input-group-btn">
			                <button type="button" class="btn btn-default" ng-click="open($event, 'start_date')"><i class="glyphicon glyphicon-calendar"></i></button>
			              </span>
			            </p>
			            </div>
			        	<div class="col-md-6" style="display: inline-block;">
			        	<b>End Date (optional)</b><br />
			            <p class="input-group">
			              <input type="text" class="form-control" uib-datepicker-popup="@{{format}}" is-open="status.end_date.opened" ng-model="end_date_obj" datepicker-options="dateOptions" close-text="Close" />
			              <span class="input-group-btn">
			                <button type="button" class="btn btn-default" ng-click="open($event, 'end_date')"><i class="glyphicon glyphicon-calendar"></i></button>
			              </span>
			            </p>
			            </div>
			        </div>
			    </div>
		        <div class="modal-footer">
		            <button class="btn btn-warning" type="button" ng-show="step>1 && !menuItemAdded" ng-click="doBack()">Back</button>
		            <button class="btn btn-primary" type="button" ng-show="step<4 && !menuItemAdded" ng-click="doNext()">Next</button>
		            <button class="btn btn-primary" type="button" ng-show="step==4 && !menuItemAdded && !id" ng-click="saveMenuItem()">Create</button>
		            <button class="btn btn-primary" type="button" ng-show="step==4 && !menuItemAdded && id" ng-click="saveMenuItem()">Update</button>
		            <button class="btn btn-primary" type="button" ng-show="menuItemAdded && doSchedule && !scheduled" ng-click="finalize()">Schedule</button>
		            <button class="btn btn-warning" type="button" ng-show="!menuItemAdded" ng-click="cancel()">Cancel</button>
		            <button class="btn btn-warning" type="button" ng-show="menuItemAdded" ng-click="cancel()">Close</button>
		        </div>
		    </script>
		</div>
		@endauth
	</div>
    <nav class="navbar navbar-default">
		<div class="container-fluid">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
					<span class="sr-only">{{ trans('labels.toggle_navigation') }}</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="">{{ app_name() }}</a>
			</div>

			<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
				<ul class="nav navbar-nav">
					<li>{!! link_to('/', trans('navs.home')) !!}</li>
					<li>{!! link_to('/restaurant/signup/lookup', trans('navs.signup')) !!}</li>
				    @permission('view_restaurants')
					<li>
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Restaurants <span class="caret"></span></a>
							<ul class="dropdown-menu" role="menu">
							    <li>{!! link_to('restaurant/manage', 'My Properties') !!}</li>
							</ul>
						</li>
					</li>
					@endauth
				    @permission('view_menu_items')
					<li>
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Menu Items <span class="caret"></span></a>
							<ul class="dropdown-menu" role="menu">
		    				    @permission('create_menu_items')
							    <li ng-controller="MenuItemInitCtrl"><a href="javascript:void(0);" onclick="openModal('menuItemModal');">Create a Menu Item</a></li>
							    @endauth
							</ul>
						</li>
					</li>
					@endauth
				    @permission('view_invoices')
					<!--li>
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Invoices <span class="caret"></span></a>
							<ul class="dropdown-menu" role="menu">
							    <li>{!! link_to('invoices/view', 'View Invoices') !!}</li>
							</ul>
						</li>
					</li-->
					@endauth
				</ul>

				<ul class="nav navbar-nav navbar-right">
					<!--li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">{{ trans('menus.language-picker.language') }} <span class="caret"></span></a>
						<ul class="dropdown-menu" role="menu">
							<li>{!! link_to('lang/en', trans('menus.language-picker.langs.en')) !!}</li>
							<li>{!! link_to('lang/es', trans('menus.language-picker.langs.es')) !!}</li>
							<li>{!! link_to('lang/fr-FR', trans('menus.language-picker.langs.fr-FR')) !!}</li>
							<li>{!! link_to('lang/it', trans('menus.language-picker.langs.it')) !!}</li>
							<li>{!! link_to('lang/pt-BR', trans('menus.language-picker.langs.pt-BR')) !!}</li>
                            <li>{!! link_to('lang/ru', trans('menus.language-picker.langs.ru')) !!}</li>
							<li>{!! link_to('lang/sv', trans('menus.language-picker.langs.sv')) !!}</li>
						</ul>
					</li-->

					@if (Auth::guest())
						<li>{!! link_to('auth/login', trans('navs.login')) !!}</li>
						<li>{!! link_to('auth/register', trans('navs.register')) !!}</li>
					@else
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">{{ Auth::user()->name }} <span class="caret"></span></a>
							<ul class="dropdown-menu" role="menu">
							    @permission('basic_client_permissions')
							    <li>{!! link_to('dashboard', trans('navs.dashboard')) !!}</li>
							    <li>{!! link_to('client/profile/update', 'Update Profile') !!}</li>
							    @endauth
							    @permission('pay_invoices')
							    <li>{!! link_to('client/payment/update', 'Update Payment Methods') !!}</li>
							    @endauth
							    <li>{!! link_to('auth/password/change', trans('navs.change_password')) !!}</li>

							    @permission('view-backend')
							        <li>{!! link_to_route('backend.dashboard', trans('navs.administration')) !!}</li>
							    @endauth

								<li>{!! link_to('auth/logout', trans('navs.logout')) !!}</li>
							</ul>
						</li>
					@endif
				</ul>
			</div>
		</div>
	</nav>
