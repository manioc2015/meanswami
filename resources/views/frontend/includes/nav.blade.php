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
						<input name="name" type="text" maxlength="127" style="width: 720px;" ng-model="menu_item.item.name" /></td><br /><br />
						<b>Short Description</b><br />
						<input name="tagline" type="text" maxlength="255" style="width: 720px;" ng-model="menu_item.item.tagline" /><br /><br />
			        	<b>Main Ingredients</b>&nbsp;(Separate with commas)<br />
			        	<textarea name="main_ingredients" style="width: 720px;" rows="2" ng-model="menu_item.item.main_ingredients"></textarea><br /><br />
			        	<b>Price Range</b><br />
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
	        			<b>Select Restaurant</b><br />
	        			<table width="100%">
	        				<tr ng-repeat="restaurant in restaurants">
	        					<td ng-show="restaurant.franchise_id" style="width: 100%;">
	        						<table width="100%">
	        							<tr>
	        								<td class="col-sm-1"><input type="radio" name="property" ng-checked="franchiseSelected(restaurant.franchise_id)" ng-click="selectFranchise(restaurant.franchise_id)" ng-value="restaurant.franchise_id" /></td>
	        								<td class="col-sm-8">@{{restaurant.franchise_name}} (Entire Franchise)</td>
	        							</tr>
	        							<tr ng-repeat="franchiseRestaurant in restaurant.restaurants">
	        								<td class="col-sm-1"><input type="radio" name="property" ng-checked="restaurantSelected($parent.restaurant.franchise_id, franchiseRestaurant.id)" ng-click="selectRestaurant($parent.restaurant.franchise_id, franchiseRestaurant.id)" ng-value="franchiseRestaurant.id" /></td>
	        								<td class="col-sm-8">@{{franchiseRestaurant.name}} (@{{franchiseRestaurant.address1}} @{{franchiseRestaurant.city}}, @{{franchiseRestaurant.state}} @{{franchiseRestaurant.zipcode}})</td>
	        							</tr>
	        						</table>
	        					</td>
	        					<td ng-show="!restaurant.franchise_id" style="width: 100%">
	        						<table width="100%">
	        							<tr>
	        								<td class="col-sm-1"><input type="radio" name="property" ng-checked="restaurantSelected(null, restaurant.id)" ng-click="selectRestaurant(null, restaurant.id)" ng-value="restaurant.id" /></td>
	        								<td class="col-sm-8">@{{restaurant.name}} (@{{restaurant.address1}} @{{restaurant.city}}, @{{restaurant.state}} @{{restaurant.zipcode}})</td>
	        							</tr>
	        						</table>
	        					</td>
	        				</tr>
	        			</table>
		        	</div>
		        </div>
		        <div ng-show="menuItemAdded && !doSchedule && !menuItemInactive" class="modal-body" style="display: inline-table; width: 100%">
		        Your menu item has been <span ng-show="!id">created</span><span ng-show="id">updated</span>. Click <a href="javascript:void(0);" ng-click="schedule();">here</a> to schedule when this menu item will be advertised.
		        Otherwise, it&#39;ll always be advertised.
		        </div>
		        <div ng-show="menuItemAdded && !doSchedule && menuItemInactive" class="modal-body" style="display: inline-table; width: 100%">
		        Your menu item has been <span ng-show="!id">created</span><span ng-show="id">updated</span>, but it is inactive because you&#39;ve exceeded the number of menu items allowed for the restaurant you&#39;ve selected. Click <a href="/restaurant/manage/adslots?restaurant_id=@{{restaurant.id}}">here</a> to increase the number of menu items allowed for the restaurant.
		        </div>
		        <div ng-show="menuItemAdded && scheduled" class="modal-body" style="display: inline-table; width: 100%">
		        Your menu item has been scheduled.
		        </div>
	        	<div ng-show="menuItemAdded && doSchedule && !scheduled" class="modal-body" style="display: inline-table; width: 100%">
		        	<b>Select Availability Days and Hours</b><br />
		        	You can enter your restaurant&#39;s hours of operation if the menu item is always available when you&#39;re open.
		        	<div style="display: inline-table; width: 100%" ng-repeat="(index, day_slot) in menu_item.availability" id="slot_@{{index}}">
			        	<div style="width: 12%; display: inline-block;"><input name="day" type="checkbox" ng-checked="daySelected('1', index)" ng-click="selectDay($event, '1', index)" ng-value="1" />Mon</div>
			        	<div style="width: 12%; display: inline-block;"><input name="day" type="checkbox" ng-checked="daySelected('2', index)" ng-click="selectDay($event, '2', index)" ng-value="2" />Tue</div>
			        	<div style="width: 12%; display: inline-block;"><input name="day" type="checkbox" ng-checked="daySelected('3', index)" ng-click="selectDay($event, '3', index)" ng-value="3" />Wed</div>
			        	<div style="width: 12%; display: inline-block;"><input name="day" type="checkbox" ng-checked="daySelected('4', index)" ng-click="selectDay($event, '4', index)" ng-value="4" />Thu</div>
			        	<div style="width: 12%; display: inline-block;"><input name="day" type="checkbox" ng-checked="daySelected('5', index)" ng-click="selectDay($event, '5', index)" ng-value="5" />Fri</div>
			        	<div style="width: 12%; display: inline-block;"><input name="day" type="checkbox" ng-checked="daySelected('6', index)" ng-click="selectDay($event, '6', index)" ng-value="6" />Sat</div>
			        	<div style="width: 12%; display: inline-block;"><input name="day" type="checkbox" ng-checked="daySelected('7', index)" ng-click="selectDay($event, '7', index)" ng-value="7" />Sun</div>
			        	<br />
		            	<button style="float: right; position: relative; bottom: 20px;" class="btn btn-warning" type="button" ng-click="remove_slot(index)">Remove</button>
			        	<div ng-repeat="(index_time, time_slot) in day_slot['times']">
				        	From: <select name="start_time" ng-model="menu_item.availability[$parent.index]['times'][index_time]['start_time']" ng-options="hour.value for hour in time_slots"></select>&nbsp;&nbsp;&nbsp;
				        	To: <select name="end_time" ng-model="menu_item.availability[$parent.index]['times'][index_time]['end_time']" ng-options="hour.value for hour in time_slots"></select>
			        	</div>
				        <hr class="schedule" />
			        </div>
			        <div style="display: inline-table; width: 100%">
			        	<span style="color: red;" ng-show="day_error">You must select at least one day.<br /></span>
			        	<div style="width: 12%; display: inline-block;"><input name="day" type="checkbox" ng-checked="daySelected('1')" ng-click="selectDay($event, '1')" ng-value="1" />Mon</div>
			        	<div style="width: 12%; display: inline-block;"><input name="day" type="checkbox" ng-checked="daySelected('2')" ng-click="selectDay($event, '2')" ng-value="2" />Tue</div>
			        	<div style="width: 12%; display: inline-block;"><input name="day" type="checkbox" ng-checked="daySelected('3')" ng-click="selectDay($event, '3')" ng-value="3" />Wed</div>
			        	<div style="width: 12%; display: inline-block;"><input name="day" type="checkbox" ng-checked="daySelected('4')" ng-click="selectDay($event, '4')" ng-value="4" />Thu</div>
			        	<div style="width: 12%; display: inline-block;"><input name="day" type="checkbox" ng-checked="daySelected('5')" ng-click="selectDay($event, '5')" ng-value="5" />Fri</div>
			        	<div style="width: 12%; display: inline-block;"><input name="day" type="checkbox" ng-checked="daySelected('6')" ng-click="selectDay($event, '6')" ng-value="6" />Sat</div>
			        	<div style="width: 12%; display: inline-block;"><input name="day" type="checkbox" ng-checked="daySelected('7')" ng-click="selectDay($event, '7')" ng-value="7" />Sun</div>
			        	<br />
		            	<button style="float: right; position: relative; bottom: 20px;" class="btn btn-primary" type="button" ng-show="new_slot.days.length && new_slot.times.length" ng-click="add_slot();">Add Slot</button>
			        	<span style="color: red;" ng-show="time_error">You must provide at least one time range.<br /></span>
			        	<div ng-repeat="(index, time_slot) in new_slot['times']">
				        	From: <select name="start_time" ng-model="new_slot['times'][index]['start_time']" ng-options="hour.value for hour in time_slots"></select>&nbsp;&nbsp;&nbsp;
				        	To: <select name="end_time" ng-model="new_slot['times'][index]['end_time']" ng-options="hour.value for hour in time_slots"></select>
			            	<button class="btn btn-xs btn-primary" type="button" ng-show="index < new_slot['times'].length" ng-click="remove_time(index);">Remove</button>
			        	</div>
			        	<div>
				        	From: <select name="start_time" ng-model="start_time" ng-options="hour.value for hour in time_slots"></select>&nbsp;&nbsp;&nbsp;
				        	To: <select name="end_time" ng-model="end_time" ng-options="hour.value for hour in time_slots"></select>
			            	<button class="btn btn-xs btn-primary" type="button" ng-click="add_time();">Add</button>
			        	</div>
			        </div>
			    </div>
		        <div class="modal-footer">
		            <button class="btn btn-primary" type="button" ng-show="step>1 && !menuItemAdded" ng-click="doBack()">Back</button>
		            <button class="btn btn-primary" type="button" ng-show="step<4 && !menuItemAdded" ng-click="doNext()">Next</button>
		            <button class="btn btn-primary" type="button" ng-show="step==4 && !menuItemAdde && !id" ng-click="create()">Create</button>
		            <button class="btn btn-primary" type="button" ng-show="step==4 && !menuItemAdded && id" ng-click="create()">Update</button>
		            <button class="btn btn-warning" type="button" ng-show="!menuItemAdded" ng-click="cancel()">Cancel</button>
		            <button class="btn btn-warning" type="button" ng-show="menuItemAdded && doSchedule && !scheduled && menu_item.availability.length" ng-click="finalize()">Schedule</button>
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
							    <li>{!! link_to('restaurant/manage', 'My Restaurants') !!}</li>
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
					<li>
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Invoices <span class="caret"></span></a>
							<ul class="dropdown-menu" role="menu">
							    <li>{!! link_to('invoices/view', 'View Invoices') !!}</li>
							</ul>
						</li>
					</li>
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
