<!doctype html>
<?php session_start();?>
<html ng-app="shorterApp" lang="en">

<head>
    <meta charset="utf-8">

    <title>URL Shorter</title>

    <link rel="stylesheet" href="https://storage.googleapis.com/code.getmdl.io/1.0.6/material.indigo-pink.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="shorter.css">
</head>

<body>
    <script src="https://storage.googleapis.com/code.getmdl.io/1.0.6/material.min.js"></script>
    <div class="mdl-layout mdl-js-layout mdl-layout--fixed-header">
        <header class="mdl-layout__header">
            <div class="mdl-layout__header-row">
                <span class="mdl-layout-title">URL Shorter</span>
            </div>
        </header>
        <main class="mdl-layout__content">
            <div class="page-content">
                <div class="mdl-grid">
                    <div class="mdl-cell mdl-cell--6-col">
                        <form action="create.php" method="POST">
                            <div class="mdl-card mdl-shadow--4dp mdl-card--border">
                                <?php
                                    if (isset($_GET["e"]) && ($_GET["e"]=="1" || $_GET["e"]=="2" || $_GET["e"]=="8" || $_GET["e"]=="9")) {
                                        echo '<div class="mdl-card__title error"><i class="fa fa-cog"></i>&nbsp;&nbsp;Create';
                                        if ($_GET["e"]=="1") echo " - <br/><small>Incorrect validity value</small>";
                                        if ($_GET["e"]=="2") echo " - <br/><small>Incorrect url</small>";
                                        if ($_GET["e"]=="8"||$_GET["e"]=="9") echo " - <br/><small>Invalid short name</small>";
                                        echo '</div>';
                                    } else {
                                        echo '<div class="mdl-card__title"><i class="fa fa-cog"></i>&nbsp;&nbsp;Create </div>';
                                    }
                                ?>
                                <div class="mdl-card__supporting-text">
                                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                        <input class="mdl-textfield__input" type="url" id="url" name="url" required title="Only valid url" value="http://" pattern="http[s]*://[A-Za-z0-9./?&#-_=]+">
                                        <label class="mdl-textfield__label" for="url">Long url</label>
                                        <span for="url" class="mdl-tooltip">URL including http:// to be shorten</span>
                                    </div>
                                    <label class="mdl-switch mdl-js-switch mdl-js-ripple-effect">
                                        <input type="checkbox" id="exp" name="exp" class="mdl-switch__input" ng-model="exp">
                                        <span id="changeExp" class="mdl-switch__label">Change expiry value</span>
                                        <span for="changeExp" class="mdl-tooltip">By default, short url will expire after 5 minutes, and will be destroy !</span>
                                    </label>
                                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label" ng-show="exp">
                                        <input class="mdl-textfield__input" type="number" id="range" name="range" ng-disabled="!exp" required pattern="[0-9]*(\.[0-9]+)?" min="0" step="1" max="120">
                                        <label class="mdl-textfield__label" for="range">Expiry value</label>
                                        <span class="mdl-textfield__error" for="range">Input is not a valid number (between 0 and 120)</span>
                                        <span for="range" class="mdl-tooltip">Validity period in minutes (between 0 and 120) - 0 for unlimited</span>
                                    </div>
                                    <label class="mdl-switch mdl-js-switch mdl-js-ripple-effect">
                                        <input type="checkbox" id="sname" name="sname" class="mdl-switch__input" ng-model="sname">
                                        <span id="changeSname" class="mdl-switch__label">Change short name</span>
                                        <span for="changeSname" class="mdl-tooltip">By default, short url will generate randomly</span>
                                    </label>
                                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label" ng-show="sname">
                                        <input class="mdl-textfield__input" type="text" id="sn" name="sn" ng-disabled="!sname" required min="5" pattern="[A-Z,a-z,0-9]{5,20}">
                                        <label class="mdl-textfield__label" for="sn">Short name</label>
                                        <span class="mdl-textfield__error" for="sn">Input is not a valid name (alphanumeric characters only, between 5 and 20 characters)</span>
                                        <span for="sn" class="mdl-tooltip">Alphanumeric name only, between 5 and 20 characters</span>
                                    </div>
                                    <label class="mdl-switch mdl-js-switch mdl-js-ripple-effect">
                                        <input type="checkbox" id="pwd" name="pwd" class="mdl-switch__input" ng-model="pwd">
                                        <span id="addPwd" class="mdl-switch__label">Add password</span>
                                        <span for="addPwd" class="mdl-tooltip">By default, short url are not password protected, you can add one for private url.</span>
                                    </label>
                                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label" ng-show="pwd">
                                        <input class="mdl-textfield__input" type="password" id="pw" name="pw" ng-disabled="!pwd" required min="8" max="50" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,50}">
                                        <label class="mdl-textfield__label" for="pw">Password</label>
                                        <span class="mdl-textfield__error" for="pw">Input is not a valid password (between 8 and 50 characters with at least one number, one uppercase and one lowercase letter)</span>
                                        <span for="pw" class="mdl-tooltip">Any characters between 8 and 50 characters with at least one number, one uppercase and one lowercase letter</span>
                                    </div>
                                    <!-- <input class="mdl-slider mdl-js-slider" ng-model="validity" type="range" min="0" max="120" step="1" id="srange" name="srange">-->
                                </div>
                                <div class="mdl-card__actions">
                                    <button class="mdl-button mdl-js-button mdl-button--raised mdl-button--colored">
                                        Create !
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="mdl-cell mdl-cell--6-col">
                        <form action="view.php" method="POST">
                            <div class="mdl-card mdl-shadow--4dp">
                                <?php
                                    if (isset($_GET["e"]) && ($_GET["e"]=="3" || $_GET["e"]=="403")) {
                                        echo '<div class="mdl-card__title error"><i class="fa fa-search"></i>&nbsp;&nbsp; Decode';
                                        if ($_GET["e"]=="3") echo " - <br/><small>Incorrect ID</small>";
                                        if ($_GET["e"]=="403") echo " - <br/><small>Incorrect Password</small>";
                                        echo '</div>';
                                    } else {
                                        echo '<div class="mdl-card__title"><i class="fa fa-search"></i>&nbsp;&nbsp; Decode </div>';
                                    }
                                ?>
                                <div class="mdl-card__supporting-text">
                                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                        <input class="mdl-textfield__input" type="text" id="id" name="c" required title="Only alphanumeric value" pattern="[a-zA-Z0-9]+">
                                        <label id="urlid" class="mdl-textfield__label" for="id">ID</label>
                                        <span for="urlid" class="mdl-tooltip">ID attach to the long url</span>
                                    </div>
                                    <label class="mdl-switch mdl-js-switch mdl-js-ripple-effect" for="r">
                                        <input type="checkbox" id="r" name=r class="mdl-switch__input">
                                        <span id="redirect" class="mdl-switch__label">Automatic redirection</span>
                                        <span for="redirect" class="mdl-tooltip">By default you will be redirected to the long encoded url to the ID above</span>
                                    </label>
                                    <label class="mdl-switch mdl-js-switch mdl-js-ripple-effect">
                                        <input type="checkbox" id="epwd" name="epwd" class="mdl-switch__input" ng-model="epwd">
                                        <span id="enterPwd" class="mdl-switch__label">Enter password</span>
                                        <span for="enterPwd" class="mdl-tooltip">By default, short url are not password protected, you can enter one to access private url.</span>
                                    </label>
                                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label" ng-show="epwd">
                                        <input class="mdl-textfield__input" type="password" id="p" name="p" ng-disabled="!epwd" required min="8" max="50" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,50}">
                                        <label class="mdl-textfield__label" for="pw">Password</label>
                                        <span class="mdl-textfield__error" for="pw">Input is not a valid password (between 8 and 50 characters with at least one number, one uppercase and one lowercase letter)</span>
                                        <span for="p" class="mdl-tooltip">Any characters between 8 and 50 characters with at least one number, one uppercase and one lowercase letter</span>
                                    </div>
                                </div>
                                <div class="mdl-card__actions">
                                    <button class="mdl-button mdl-js-button mdl-button--raised mdl-button--colored">
                                        Decode
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </main>
        <footer class="mdl-mini-footer">
          <?php
          $nb=$nb_perma=$nb_current=0;
          include("config.php");
          // Create connection
          $conn = new mysqli($db_ip, $db_login, $db_pwd,$db_name);
          if (!$conn->connect_error) {
            $sql = "SELECT MAX(id)+1 as nb FROM list";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
              while($row = $result->fetch_assoc()) {
                $nb=$row['nb']==''?0:$row['nb'];
              }
            }
            $sql = "SELECT COUNT(id) as nb FROM list WHERE validity IS NULL";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
              while($row = $result->fetch_assoc()) {
                $nb_perma=$row['nb'];
              }
            }
            $sql = "SELECT COUNT(id) as nb FROM list WHERE NOW() BETWEEN creation AND validity";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
              while($row = $result->fetch_assoc()) {
                $nb_current=$row['nb'];
              }
            }
          }
          ?>
          <div class="mdl-mini-footer__left-section">
            <span class="mdl-badge" data-badge="<?=$nb?>">created</span>
            <span class="mdl-badge" data-badge="<?=$nb_perma?>">permanent</span>
            <span class="mdl-badge" data-badge="<?=$nb_current?>">current</span>
          </div>
        </footer>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.4.8/angular.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.4.8/angular-route.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.4.8/angular-animate.min.js"></script>
    <script src="jsLib/qrcodelib.js"></script>
    <script src="jsLib/qrcode.js"></script>

    <script src="shorter.js"></script>
</body>

</html>
