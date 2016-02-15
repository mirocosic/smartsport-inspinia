  <nav class="navbar-default navbar-static-side" role="navigation">
        <div class="sidebar-collapse">
            <ul class="nav metismenu" id="side-menu">
                <li class="nav-header">
                    <div class="dropdown profile-element">
                        <span>
                            <img alt="image" class="img-circle" src="/img/<?=$_SESSION['Auth']['ProfileImg'];?>" width="70">
                             </span>
                            <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                            <span class="clear"> <span class="block m-t-xs">
                                    <strong class="font-bold">
                                        <?=AuthComponent::user('fullname');?>
                                    </strong>
                             </span> <span class="text-muted text-xs block">
                                    <?=AuthComponent::user('role');?>
                                    <b class="caret"></b></span> </span> </a>
                            <ul class="dropdown-menu animated fadeIn m-t-xs">
                                <li><a href="#"><?=__("Profile");?></a></li>
                                <li><a href="/users/logout"><?=__("Logout");?></a></li>
                            </ul>
                    </div>
                    <div class="logo-element">
                        SS
                    </div>
                </li>
                <?if($acl->check(array('User' => $user), 'controllers/Users')):?>
                    <li <?if($this->params['controller'] == 'users'){echo 'class="active"';}?>>
                    <a href="#"><i class="fa fa-user fa-fw"></i><span class="nav-label"><?=__("Profile");?></span>
                        <span class="fa arrow fa-fw"></span>
                    </a>
                    <ul class="nav nav-second-level collapse">
                        <li <?if($this->params['action'] == 'profile'){echo 'class="active"';}?>>
                            <a href="/users/profile"><i class="fa fa-eye fa-fw"></i><span class="nav-label"><?=__("View");?></span></a>
                        </li>
                        <li>
                            <a href="/profile/view"><i class="fa fa-pencil fa-fw"></i><span class="nav-label"><?=__("Edit");?></span></a>
                        </li>
                        <li <?if($this->params['action'] == 'weight'){echo 'class="active"';}?>>
                            <a href="/users/weight"><i class="fa fa-shopping-bag fa-fw"></i><span class="nav-label"><?=__("Weight");?></span></a>
                        </li>
                    </ul>
                </li>
                <?endif;?>

                <?if($acl->check(array('User' => $user), 'controllers/Clubs')):?>
                    <li <?if($this->params['controller'] == 'clubs' ||$this->params['controller'] == 'club'){echo 'class="active"';}?>>
                    <a href="/clubs/view"><i class="fa fa-slideshare fa-fw"></i> <span class="nav-label"><?=__("Club");?></span> <span class="fa arrow fa-fw"></span></a>
                    <ul class="nav nav-second-level collapse">
                        <li <?if($this->params['action'] == 'view'){echo 'class="active"';}?>>
                            <a href="/clubs/view"><i class="fa fa-eye fa-fw"></i><span class="nav-label"><?=__("Profile");?></span></a>
                        </li>
                         <li <?if($this->params['action'] == 'members'){echo 'class="active"';}?>>
                            <a href="/clubs/members"><i class="fa fa-user fa-fw"></i><span class="nav-label"><?=__("Members");?></span></a>
                        </li>
                        <li <?if($this->params['action'] == 'groups'){echo 'class="active"';}?>>
                            <a href="/clubs/groups"><i class="fa fa-users fa-fw"></i> <span class="nav-label"><?=__("Groups");?></span></a>
                        </li>
                        <li  <?if($this->params['action'] == 'fees'){echo 'class="active"';}?>>
                            <a href="/clubs/fees"><i class="fa fa-money fa-fw"></i> <span class="nav-label"><?=__('Fees');?></span></a>
                        </li>
                        <li <?if($this->params['action'] == 'events'){echo 'class="active"';}?>>
                            <a href="/clubs/events"><i class="fa fa-check-square-o fa-fw"></i><span class="nav-label">Evidencija treninga</span></a>
                        </li>
                        <li>
                            <a href="#"><i class="fa fa-area-chart fa-fw"></i> <span class="nav-label">Statistike</span></a>
                        </li>
                        <li>
                            <a href="/clubs/calendar"><i class="fa fa-calendar fa-fw"></i> <span class="nav-label"><?=__('Calendar');?></span></a>
                        </li>
                        <li>
                            <a href="#"><i class="fa fa-book fa-fw"></i> <span class="nav-label"><?=__('Reports');?></span></a>
                        </li>
                    </ul>
                </li>
                <?endif;?>
                <?if($acl->check(array('User' => $user), 'controllers/Alliance')):?>
                    <li <?if($this->params['controller'] == 'alliance'){echo 'class="active"';}?>>
                    <a href="/alliance/"><i class="fa fa-bank fa-fw"></i> <span class="nav-label"><?=__("Alliance");?></span> <span class="fa arrow fa-fw"></span></a>
                    <ul class="nav nav-second-level collapse">
                        <li <?if($this->params['action'] == 'clubs'){echo 'class="active"';}?>>
                            <a href="#"><i class="fa fa-slideshare fa-fw"></i><span class="nav-label"><?=__("Clubs");?></span></a>
                        </li>
                        <li <?if($this->params['action'] == 'licences'){echo 'class="active"';}?>>
                            <a href="#"><i class="fa fa-certificate fa-fw"></i><span class="nav-label"><?=__("Licences");?></span></a>
                        </li>
                        <li>
                            <a href="#"><i class="fa fa-book fa-fw"></i> <span class="nav-label"><?=__('Reports');?></span></a>
                        </li>
                    </ul>
                </li>
                <?endif;?>
                <?if($acl->check(array('User' => $user), 'controllers/Competitions')):?>
                    <li <?if($this->params['controller'] == 'competitions'){echo 'class="active"';}?>>
                    <a href="/alliance/"><i class="fa fa-trophy fa-fw"></i> <span class="nav-label"><?=__("Competitions");?></span> <span class="fa arrow fa-fw"></span></a>
                    <ul class="nav nav-second-level collapse">
                        <li <?if($this->params['action'] == 'create'){echo 'class="active"';}?>>
                            <a href="#"><i class="fa fa-plus fa-fw"></i><span class="nav-label"><?=__("Create");?></span></a>
                        </li>

                    </ul>
                </li>
                <?endif;?>

                <?if($acl->check(array('User' => $user), 'controllers/Admin')):?>
                    <li <?if($this->params['controller'] == 'admin'){echo 'class="active"';}?>>
                    <a href="#"><i class="fa fa-fort-awesome fa-fw"></i> <span class="nav-label">Admin</span><span class="fa arrow fa-fw"></span></a>
                    <ul class="nav nav-second-level collapse">
                        <li>
                            <a href="/admin/users"><i class="fa fa-users fa-fw"></i><span class="nav-label"><?=__("Users");?></span></a>
                        </li>
                        <li>
                            <a href="/admin/clubs"><i class="fa fa-slideshare fa-fw"></i><span class="nav-label"><?=__("Clubs");?></span></a>
                        </li>
                        <li>
                            <a href="#"><i class="fa fa-bank fa-fw"></i><span class="nav-label"><?=__("Alliances");?></span></a>
                        </li>
                        <li>
                            <a href="#"><i class="fa fa-trophy fa-fw"></i><span class="nav-label"><?=__("Competitions");?></span></a>
                        </li>
                        <li>
                            <a href="/admin/applyAco"><i class="fa fa-lock fa-fw"></i><span class="nav-label"><?=__("Permisions");?></span></a>
                        </li>
                    </ul>
                    
                </li>
                <?endif;?>
                <li <?if($this->params['controller'] == 'future'){echo 'class="active"';}?>>
                    <a href="/#"><i class="fa fa-magic "></i> <span class="nav-label "><?=__("Tha Future");?></span> </a>
                </li>
                <li <?if($this->params['controller'] == 'settings'){echo 'class="active"';}?>>
                    <a href="/settings"><i class="fa fa-cog"></i> <span class="nav-label"><?=__("Settings");?></span> </a>
                </li>
            </ul>

        </div>
    </nav>