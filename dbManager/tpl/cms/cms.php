<?php
require_once '../../real_path.inc.php';
require $root.'/dbManager/session.php';
?>
<div class="container-fluid display-table">
    <div class="row flex-nowrap display-table-row">
        <div id="side-menu" style="overflow-y: scroll; height: 100vh;"
             class="col-3 col-sm-1 col-md-1 col-lg-2 d-sm-block collapse navbar-collapse">
            <h1>עד המדינה</h1>
            <ul>
                <li class="link" ui-sref-active="active">
                    <a ui-sref="CMS.home">
                        <span class="fa fa-th" aria-hidden="true"></span>
                        <span class="d-lg-inline-block title">פאנל ניהול</span>
                    </a>
                </li>
                <li class="link" ui-sref-active="active">
                    <a>
                        <span class="fa fa-list-alt" aria-hidden="true"></span>
                        <span class="d-lg-inline-block title">כתבות</span>
                        <span class="badge badge-success
                        pull-left d-lg-inline-block notification">20</span>
                        <ul class="collpseableSide" id="collapse-post">
                            <li><a ui-sref="CMS.new-article">צור חדש</a></li>
                            <li><a ui-sref="CMS.articles">צפה בכתבות</a></li>
                        </ul>
                    </a>
                </li>
                <li class="link" ui-sref-active="active">
                    <a>
                        <span class="fa fa-list-alt" aria-hidden="true"></span>
                        <span class="d-lg-inline-block title">דפים</span>
                        <span class="badge badge-success
                        pull-left d-lg-inline-block notification">20</span>
                        <ul class="collpseableSide" id="collapse-pages">
                            <li><a ui-sref="CMS.pages">כותרות</a></li>
                        </ul>
                    </a>
                </li>
                <li class="link" ui-sref-active="active">
                    <a>
                        <span class="fa fa-list-alt" aria-hidden="true"></span>
                        <span class="d-lg-inline-block title">תגובות</span>
                        <ul class="collpseableSide" id="collapse-comments">
                            <li>
                                <a ui-sref="CMS.allComments">כל התגובות
                                    <span class="badge badge-warning
                                 pull-right d-lg-inline-block notification ">10</span>

                                </a>
                            </li>
                            <li><a ui-sref="CMS.pending">ממתינות
                                    <span class="badge badge-warning
                                 pull-right d-lg-inline-block notification ">10</span>

                                </a></li>
                            <li><a ui-sref="CMS.approved">מאושרות
                                    <span class="badge badge-success
                            pull-right d-lg-inline-block notification">10</span>
                                </a></li>
                            <li><a ui-sref="CMS.unapproved">לא מאושרות
                                    <span class="badge badge-danger
                                 pull-right d-lg-inline-block notification ">10</span>

                                </a></li>
                        </ul>
                    </a>
                </li>
                <li class="link" ui-sref-active="active">
                    <a ui-sref="CMS.commenters">
                        <span class="fa fa-user" aria-hidden="true"></span>
                        <span class="d-lg-inline-block title">מגיבים</span>
                    </a>
                </li>

                <li class="link" ui-sref-active="active">
                    <a ui-sref="CMS.tags">
                        <span class="fa fa-tags" aria-hidden="true"></span>
                        <span class="d-lg-inline-block title">תגיות</span>
                    </a>
                </li>
                <li class="link" ui-sref-active="active">
                    <a ui-sref="CMS.users">
                        <span class="fa fa-users" aria-hidden="true"></span>
                        <span class="d-lg-inline-block title">כתבים</span>
                        <ul class="collpseableSide" id="collapse-reporters">
                            <li>
                                <a ui-sref="CMS.allReporters">כל הכתבים
                                    <span class="badge badge-success
                                 pull-right d-lg-inline-block notification ">10</span>

                                </a>
                            </li>
                            <li>
                                <a ui-sref="CMS.reporter">הוסף כתב
                                    <span class="badge badge-info
                                 pull-right d-lg-inline-block notification ">10</span>

                                </a>
                            </li>
                        </ul>

                    </a>
                </li>
                <li class="link settings-btn" ui-sref-active="activeSettings">
                    <a ui-sref="CMS.settings">
                        <span class="fa fa-cog" aria-hidden="true"></span>
                        <span class="d-lg-inline-block title">הגדרות</span>
                    </a>
                </li>
            </ul>
        </div>
        <div class="col dtc box" style="padding: 0;">

            <header class="row" id="nav-header" style="padding: 10px;">
                <div class="col-md-5">
                    <nav class="navbar bg-faded d-sm-none">
                        <button style="height: 40px;" class="navbar-toggler" type="button"
                                data-toggle="collapse" data-target="#side-menu" aria-controls="side-menu"
                                aria-expanded="false" aria-label="Toggle navigation">
                            <i class="

                        fa fa-bars fa-lg"></i>
                        </button>

                    </nav>
                    <input type="text" class="form-control d-lg-block" id="headSearch" placeholder="חפש במערכת ...">
                </div>
                <div class="col-md-7">
                    <ul class="pull-left">
                        <li class="iconLabel">
                            <a href="#">
                                <i class="fa fa-bell" aria-hidden="true"></i>
                                <span class="badge badge-warning labelCount">3</span>
                            </a>
                        </li>
                        <li class="iconLabel">
                            <a href="#">
                                <i class="fa fa-envelope" aria-hidden="true"></i>
                                <span class="badge badge-info labelCount">3</span>
                            </a>
                        </li>
                        <li>
                            <a href="tpl/login/logout.php">
                                <i class="fa fa-sign-out" aria-hidden="true"></i>
                                <span>התנתק</span>
                            </a>
                        </li>
                        <li id="welcome" class="d-lg-block">
                            <span>ברוך הבא למערכת</span>
                            <strong><?= $_SESSION['userName'] ?></strong>

                        </li>

                    </ul>
                </div>
            </header>
            <ui-view>

            </ui-view>
            <footer class="row" id="admin-footer">
                <div style="flex:1;">
                    <div class="pull-left">Copyright &copy; <?= date("Y"); ?></div>
                    <div class="pull-right">מערכת "עד המדינה"</div>
                </div>
            </footer>

        </div>
    </div>


</div>