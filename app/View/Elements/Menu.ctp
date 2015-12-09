  <nav class="navbar-default navbar-static-side" role="navigation">
        <div class="sidebar-collapse">
            <ul class="nav metismenu" id="side-menu">
                <li class="nav-header">
                    <div class="dropdown profile-element">
                        <span>
                            <img alt="image" class="img-circle" src="/img/profilna.jpg" width="70">
                             </span>
                            <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                            <span class="clear"> <span class="block m-t-xs"> <strong class="font-bold">Miro Ćosić</strong>
                             </span> <span class="text-muted text-xs block">CTO <b class="caret"></b></span> </span> </a>
                            <ul class="dropdown-menu animated fadeIn m-t-xs">
                                <li><a href="#"><?=__("Profile");?></a></li>
                                <li><a href="/users/logout"><?=__("Logout");?></a></li>
                            </ul>
                    </div>
                    <div class="logo-element">
                        SS
                    </div>
                </li>
                 <li <?if($this->params['controller'] == 'clubs' ||$this->params['controller'] == 'club'){echo 'class="active"';}?>>
                    <a href="/clubs/view"><i class="fa fa-slideshare"></i> <span class="nav-label"><?=__("Club");?></span> <span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level collapse">
                         <li <?if($this->params['action'] == 'members'){echo 'class="active"';}?>>
                            <a href="/clubs/members"><i class="fa fa-users"></i><span class="nav-label"><?=__("Members");?></span></a>
                        </li>
                        <li <?if($this->params['action'] == 'groups'){echo 'class="active"';}?>>
                            <a href="/club/groups"><i class="fa fa-users"></i> <span class="nav-label"><?=__("Groups");?></span></a>
                        </li>
                        <li>
                            <a href="#"><i class="fa fa-money"></i> <span class="nav-label"><?=__('Fees');?></span></a>
                        </li>
                        <li>
                            <a href="#"><i class="fa fa-file-text-o"></i><span class="nav-label">Evidencija treninga</span></a>
                        </li>
                        <li>
                            <a href="#"><i class="fa fa-area-chart"></i> <span class="nav-label">Statistike</span></a>
                        </li>
                        <li>
                            <a href="#"><i class="fa fa-calendar"></i> <span class="nav-label"><?=__('Calendar');?></span></a>
                        </li>
                    </ul>
                </li>
                
               
                <li <?if($this->params['controller'] == 'admin'){echo 'class="active"';}?>>
                    <a href="#"><i class="fa fa-magic"></i> <span class="nav-label">Admin</span><span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level collapse">
                        <li>
                            <a href="/admin/users"><i class="fa fa-users"></i><span class="nav-label"><?=__("Users");?></span></a>
                        </li>
                        <li>
                            <a href="/admin/clubs"><i class="fa fa-slideshare"></i><span class="nav-label"><?=__("Clubs");?></span></a>
                        </li>
                        <li>
                            <a href="#"><i class="fa fa-bank"></i><span class="nav-label"><?=__("Alliances");?></span></a>
                        </li>
                        <li>
                            <a href="#"><i class="fa fa-trophy"></i><span class="nav-label"><?=__("Competitions");?></span></a>
                        </li>
                        <li>
                            <a href="#"><i class="fa fa-lock"></i><span class="nav-label"><?=__("Permisions");?></span></a>
                        </li>
                    </ul>
                    
                </li>
                <li>
                    <a href="#"><i class="fa fa-cog"></i> <span class="nav-label"><?=__("Settings");?></span> </a>
                </li>
            </ul>

        </div>
    </nav>