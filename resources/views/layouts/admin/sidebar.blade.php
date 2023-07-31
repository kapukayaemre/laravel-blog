<div class="app-sidebar">
    <div class="logo">
        <a href="index.html" class="logo-icon"><span class="logo-text">Neptune</span></a>
        <div class="sidebar-user-switcher user-activity-online">
            <a href="#">
                <img src="{{ asset("assets/admin/images/avatars/avatar.png") }}">
                <span class="activity-indicator"></span>
                <span class="user-info-text">Chloe<br><span class="user-state-info">On a call</span></span>
            </a>
        </div>
    </div>
    <div class="app-menu">
        <ul class="accordion-menu">
            <li class="sidebar-title">
                Apps
            </li>

            <li class="{{ Route::is('home') ? "active-page" : "" }}">
                <a href="{{ route('home') }}" class="{{ Route::is("home") ? "active" : "" }}">
                    <i class="material-icons-two-tone">dashboard</i>
                    Dashboard
                </a>
            </li>

            <li class="{{ Route::is("article.*") ? "open" : "" }}">
                <a href="#">
                    <i class="material-icons-two-tone">receipt_long</i>
                    Articles
                    <i class="material-icons has-sub-menu">keyboard_arrow_right</i>
                </a>
                <ul class="sub-menu" style="">
                    <li>
                        <a href="{{ route('article.create') }}" class="{{ Route::is("article.create") ? "active" : "" }}">New Article</a>
                    </li>
                    <li>
                        <a href="{{ route('article.index') }}" class="{{ Route::is("article.index") ? "active" : "" }}">Article List</a>
                    </li>
                </ul>
            </li>

            <li class="{{ Route::is("category.*") ? "open" : "" }}">
                <a href="#">
                    <i class="material-icons-two-tone">category</i>
                    Categories
                    <i class="material-icons has-sub-menu">keyboard_arrow_right</i>
                </a>
                <ul class="sub-menu" style="">
                    <li>
                        <a href="{{ route('category.create') }}" class="{{ Route::is("category.create") ? "active" : "" }}">New Category</a>
                    </li>
                    <li>
                        <a href="{{ route('category.index') }}" class="{{ Route::is("category.index") ? "active" : "" }}">Category List</a>
                    </li>
                </ul>
            </li>

            <li class="{{ Route::is('settings') ? "active-page" : "" }}">
                <a href="{{ route('settings') }}" class="{{ Route::is("settings") ? "active" : "" }}">
                    <i class="material-icons-two-tone">settings</i>
                    Settings
                </a>
            </li>

        </ul>
    </div>
</div>
