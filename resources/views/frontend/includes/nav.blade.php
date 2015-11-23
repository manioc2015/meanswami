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
							    <li>{!! link_to('restaurants/manage', 'View Restaurants') !!}</li>
							</ul>
						</li>
					</li>
					@endauth
				    @permission('view_menu_items')
					<li>
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Menu Items <span class="caret"></span></a>
							<ul class="dropdown-menu" role="menu">
							    <li>{!! link_to('menuitems/manage', 'View Menu Items') !!}</li>
		    				    @permission('create_menu_items')
							    <li>{!! link_to('menuitems/manage/add', 'Add Menu Item') !!}</li>
							    @endauth
							</ul>
						</li>
					</li>
					@endauth
				    @permission('view_ad_slots')
					<li>
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Ad Slots <span class="caret"></span></a>
							<ul class="dropdown-menu" role="menu">
							    <li>{!! link_to('adslots/manage', 'View Ad Slots') !!}</li>
		    				    @permission('manage_ad_slots')
							    <li>{!! link_to('adslots/manage/add', 'Add Ad Slot') !!}</li>
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
							    @permission('update_client_profile')
							    <li>{!! link_to('dashboard', trans('navs.dashboard')) !!}</li>
							    <li>{!! link_to('client/profile/update', 'Update Profile') !!}</li>
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
