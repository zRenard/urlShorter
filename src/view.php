<?php
if (isset($_GET["c"])||isset($_POST["c"])) {
    $code = htmlspecialchars(isset($_GET["c"]) ? $_GET["c"] : $_POST["c"]);
    if ($code=='') {
        header('Location: main?e=3');
        exit;
    }
    $redirect = 1;
    if (isset($_GET["r"])||isset($_POST["r"]))
      $redirect = htmlspecialchars(isset($_GET["r"]) ? $_GET["r"] : $_POST["r"]);

    include("config.php");

    // Check connection
    if ($conn->connect_error) {
      header('Location: main?e=5');
      exit;
    }

    $sql = "SELECT url,validity,hits FROM list WHERE code = '".$code."' AND (validity is NULL OR (NOW() BETWEEN creation AND validity))";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
          $sql_hits = "UPDATE list SET hits = hits+1 WHERE code = '".$code."'";
          $result_hits = $conn->query($sql_hits);

          /* getting all data for loggin */
          $remote_addr = getenv("REMOTE_ADDR");
          $remote_host = gethostbyaddr($remote_addr);
          $server_name = getenv("SERVER_NAME");
          $server_port = getenv("SERVER_PORT");
          $request_method = getenv("REQUEST_METHOD");
       	  $request_status = getenv("REDIRECT_STATUS");
          $request_uri = getenv("REQUEST_URI");
          $document_root =  getenv("DOCUMENT_ROOT");
          $user_agent = getenv("HTTP_USER_AGENT");
          $referer = getenv("HTTP_REFERER");

          $sql_hits = "INSERT INTO hits VALUES ('0','$code',NOW(),'$remote_addr','$remote_host','$server_name','$server_port','$request_method','$request_uri','$user_agent','$referer','$request_status')";
          $result_hits = $conn->query($sql_hits);

          if ($redirect==0) {
            header('Location: '.urldecode(base64_decode($row["url"])));
          } else {
            ?>
    <!doctype html>
    <html ng-app="shorterApp" lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="description" content="URLShorter">

        <title>URLShorter</title>

        <link rel="stylesheet" href="https://storage.googleapis.com/code.getmdl.io/1.0.6/material.indigo-pink.min.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
        <link rel="stylesheet" href="shorter.css">
    </head>

    <body>
      <script src="https://storage.googleapis.com/code.getmdl.io/1.0.6/material.min.js"></script>
      <div class="mdl-card mdl-shadow--4dp mdl-card--border">
        <div class="mdl-card__title"><i class="fa fa-link"></i>&nbsp;&nbsp; Short URL - <?=$code?></div>
          <div class="mdl-card__supporting-text">
            <?php $qrUrl='http://'.$_SERVER['HTTP_HOST'].'/'.$code;  ?>
            <qrcode size="180" version="40" data="<?=$qrUrl?>" title="<?=$qrUrl?>"></qrcode>
          </div>
          <div class="mdl-card__supporting-text">
            <label class="mdl-switch mdl-js-switch mdl-js-ripple-effect">
                <input type="checkbox" id="details" name="details" class="mdl-switch__input" ng-model="details">
                <span id="changeDetails" class="mdl-switch__label">Show details</span>
                <span for="changedetails" class="mdl-tooltip">Expiration time, short and long url</span>
            </label>
          </div>
          <div class="mdl-card__supporting-text" ng-show="details">
            <table class="mdl-data-table mdl-js-data-table mdl-shadow--2dp" style="white-space:normal">
              <tbody>
                <tr>
                  <td class="mdl-data-table__cell--non-numeric">Short Url</td>
                  <td class="mdl-data-table__cell--non-numeric"><a ng-href="<?=$qrUrl?>"><?=$qrUrl?></a></td>
                </tr>
                <tr>
                  <td class="mdl-data-table__cell--non-numeric">Long Url</td>
                  <td class="mdl-data-table__cell--non-numeric">
                    <textarea rows="4" disabled readonly><?=urldecode(base64_decode($row['url']))?></textarea>
                  </td>
                </tr>
                <tr>
                  <td class="mdl-data-table__cell--non-numeric">Validity</td>
                  <td class="mdl-data-table__cell--non-numeric">
                    <?
                    if ($row['validity']==NULL) {
                      echo "Never expire";
                    } else {
                      $date1 = new DateTime("now");
                      $date2 = new DateTime($row['validity']);
                      $interval = date_diff($date1, $date2);
                      if ($interval->h <1) {
                        echo $interval->format('%i minute(s) %s seconde(s)');
                      } else {
                        echo $interval->format('%h hour(s) %i minute(s) %s seconde(s)');
                      }
                    }
                    ?>
                  </td>
                </tr>
                <tr>
                  <td class="mdl-data-table__cell--non-numeric">Hits</td>
                  <td class="mdl-data-table__cell--non-numeric"><?=$row['hits']+1;?></td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class="mdl-card__actions">
            <a class="mdl-button mdl-js-button mdl-button--raised mdl-button--colored" href="<?=urldecode(base64_decode($row['url']))?>">
                Open
            </a>
          </div>
        </div>
        <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.4.8/angular.min.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.4.8/angular-route.min.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.4.8/angular-animate.min.js"></script>
        <script src="jsLib/qrcodelib.js"></script>
        <script src="jsLib/qrcode.js"></script>

        <script src="shorter.js"></script>
    </body>

    </html>
    <?php
          }
      }
    } else {
      ?>
<!doctype html>
<html ng-app="shorterApp" lang="en">
<head>
  <meta charset="utf-8">

  <title>URLShorter</title>

  <link rel="stylesheet" href="https://storage.googleapis.com/code.getmdl.io/1.0.6/material.indigo-pink.min.css">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="shorter.css">
</head>

<body>
<div class="mdl-card mdl-shadow--4dp mdl-card--border">
  <div class="mdl-card__title error"><i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp; Error</div>
    <div class="mdl-card__supporting-text">
      Not Found
    </div>
  </div>
</body>

</html><?php
    }
} else {
  header('Location: main?e=4');
  exit;
} ?>
