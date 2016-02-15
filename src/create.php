<?php
if (  (isset($_POST["url"]) && $_POST["url"]!="") ||
      (isset($_GET["url"]) && $_GET["url"]!="") ) {
    $url = strip_tags(isset($_GET["url"]) ? $_GET["url"] : $_POST["url"]);
    if (strcmp($url,"http://")==0 || strcmp($url,"http://www")==0) {
        header('Location: main?e=2');
        exit;
    }
    $validity = 5;
    if (isset($_POST["range"])) {
      $validity = strip_tags($_POST["range"]);
    }
    if ($validity>120 || $validity<0) {
        header('Location: main?e=1');
        exit;
    }
    $code = hash('adler32',uniqid(),false);
    if (isset($_POST["sn"])) {
      $code = strtolower(strip_tags($_POST["sn"]));
    }
    if (preg_match('/^[A-Za-z0-9]{5,20}$/', $code)==0) {
        header('Location: main?e=9&c='+$code);
        exit;
    }

    include("config.php");
    
    // Check connection
    if ($conn->connect_error) {
      header('Location: main?e=5');
      exit;
    }

    //$sql = "DELETE FROM `zrdli_list` WHERE validity IS NOT NULL AND validity < now()";
    //$result = $conn->query($sql);

    /* Check if url already exist */
    /* SELECT * FROM `list` WHERE  url = TO_BASE64('https%3A%2F%2Fncebpm1138web01.etv.nce.amadeus.net%2FJLInt%2Fdyn%2Fair%2Fbooking%2Favailability%3FARRIVAL_LOCATION_1%3DTYO%26amp%3BCFF_1%3DJALINT%26amp%3BCONTACT_POINT_BUSINESS_PHONE_1%3D%252B33%25200054000000%26amp%3BCONTACT_POINT_EMAIL_1%3Dtest%2540test.com%26amp%3BCONTACT_POINT_HOME_PHONE_1%3D%252B33%25200455557733%26amp%3BCONTACT_POINT_MOBILE_PHONE_1%3D%252B33%25200400667788%26amp%3BCOUNTRY_SITE%3DJAL_ER_FR%26amp%3BDATE_OF_BIRTH_1%3D194703150000%26amp%3BDEPARTURE_DATE_1%3D201601110000%26amp%3BDEPARTURE_LOCATION_1%3DPAR%26amp%3BDEVICE_TYPE%3DDESKTOP%26amp%3BD_ADDRESS_1%3Daddress1%26amp%3BD_ADDRESS_2%3Daddress2%26amp%3BD_ZIPCODE%3D06456%26amp%3BFIRST_NAME_1%3Dfirstname%26amp%3BFLOW_MODE%3DREVENUE%26amp%3BFORCE_OVERRIDE%3DTRUE%26amp%3BLANGUAGE%3DGB%26amp%3BLAST_NAME_1%3Dsecondname%26amp%3BNATIONALITY_1%3DFR%26amp%3BNB_ADT%3D1%26amp%3BNB_CHD%3D0%26amp%3BNB_INF%3D0%26amp%3BPASSWORD_1%3Ddummy%26amp%3BPASSWORD_2%3Ddummy%26amp%3BPATTERN%3D1B%26amp%3BPREF_AIR_FREQ_AIRLINE_1_1%3DJL%26amp%3BPREF_AIR_FREQ_LEVEL_1_1%3DGOLD%26amp%3BPREF_AIR_FREQ_MILES_1_1%3D123444%26amp%3BPREF_AIR_FREQ_NUMBER_1_1%3D11223531%26amp%3BPREF_AIR_FREQ_PIN_1_1%3D1234%26amp%3BPREF_AIR_MEAL_1%3DSTRD%26amp%3BSITE%3DJ019J019%26amp%3BSO_SITE_APIV2_SERVER%3D194.156.170.78%26amp%3BSO_SITE_APIV2_SERVER_PWD%3DTAZ%26amp%3BSO_SITE_APIV2_SERVER_USER_ID%3DGUEST%26amp%3BSO_SITE_CORPORATE_ID%3DSEP-UAT%26amp%3BSO_SITE_SI_1AXML_FROM%3DSEP_JCP%26amp%3BSO_SITE_SI_PASSWORD%3DUNSET%26amp%3BSO_SITE_SI_SAP%3D1ASIXJCPU%26amp%3BSO_SITE_SI_SERVER_IP%3D193.23.185.67%26amp%3BSO_SITE_SI_SERVER_PORT%3D18006%26amp%3BSO_SITE_SI_USER%3DUNSET%26amp%3BTITLE_1%3DMR%26amp%3BTRAVELLER_ID%3D0100%26amp%3BTRIP_FLOW%3DYES%26amp%3BTYPE_1%3DADT%26amp%3BUSER_ID%3D0100') */
    $sql = "SELECT url,code FROM list WHERE url = '".base64_encode(urlencode($url))."' AND (validity is NULL OR (NOW() BETWEEN creation AND validity))";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            header('Location: view?c='.$row["code"]);
        }
    } else {
        /* check if code already exist */
        $sql = "SELECT code FROM list WHERE code = ".$code;
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                header('Location: main?e=8');
                exit;
            }
        }
        if ($validity == 0) {
            $sql = "INSERT INTO list (url,code) VALUES ('".base64_encode(urlencode($url))."', '".$code."')";
        } else {
            $sql = "INSERT INTO list (url,code,creation,validity) VALUES ('".base64_encode(urlencode($url))."', '".$code."',NOW(),DATE_ADD(NOW(),INTERVAL '".$validity."' MINUTE))";
        }
        if ($conn->query($sql) === TRUE) {
            header('Location: view?c='.$code);
        } else {
          header('Location: main?e=6');
          exit;
        }
    }
} else {
  header('Location: main?e=7');
  exit;
} ?>
