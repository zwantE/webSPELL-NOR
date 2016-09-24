<link rel="stylesheet" type="text/css" media="all" href="css/style-nav.css">
<?php
/*
##########################################################################
#                                                                        #
#           Version 4       /                        /   /               #
#          -----------__---/__---__------__----__---/---/-               #
#           | /| /  /___) /   ) (_ `   /   ) /___) /   /                 #
#          _|/_|/__(___ _(___/_(__)___/___/_(___ _/___/___               #
#                       Free Content / Management System                 #
#                                   /                                    #
#                                                                        #
#                                                                        #
#   Copyright 2005-2015 by webspell.org                                  #
#                                                                        #
#   visit webSPELL.org, webspell.info to get webSPELL for free           #
#   - Script runs under the GNU GENERAL PUBLIC LICENSE                   #
#   - It's NOT allowed to remove this copyright-tag                      #
#   -- http://www.fsf.org/licensing/licenses/gpl.html                    #
#                                                                        #
#   Code based on WebSPELL Clanpackage (Michael Gruber - webspell.at),   #
#   Far Development by Development Team - webspell.org                   #
#                                                                        #
#   visit webspell.org                                                   #
#                                                                        #
##########################################################################
*/

$_language->readModule('moduls', false, true);

if (!ispageadmin($userID) || mb_substr(basename($_SERVER[ 'REQUEST_URI' ]), 0, 15) != "admincenter.php") {
    die($_language->module[ 'access_denied' ]);
}

if (isset($_GET[ 'delete' ])) {
    $CAPCLASS = new \webspell\Captcha;
    if ($CAPCLASS->checkCaptcha(0, $_GET[ 'captcha_hash' ])) {
        $modulID = (int)$_GET[ 'modulID' ];
        safe_query("DELETE FROM " . PREFIX . "moduls WHERE modulID='" . $modulID . "' ");
        
    } else {
        echo $_language->module[ 'transaction_invalid' ];
    }
} elseif (isset($_POST[ 'sortieren' ])) {
    $CAPCLASS = new \webspell\Captcha;
    if ($CAPCLASS->checkCaptcha(0, $_POST[ 'captcha_hash' ])) {
        $sort = $_POST[ 'sort' ];
        foreach ($sort as $sortstring) {
            $sorter = explode("-", $sortstring);
            safe_query("UPDATE " . PREFIX . "moduls SET sort='".$sorter[1]."' WHERE modulID='".$sorter[0]."' ");
        }
    } else {
        echo $_language->module[ 'transaction_invalid' ];
    }
} elseif (isset($_POST[ 'save' ])) {
    $CAPCLASS = new \webspell\Captcha;
    if ($CAPCLASS->checkCaptcha(0, $_POST[ 'captcha_hash' ])) {
        $module = $_POST[ 'module' ];
        
        if (isset($_POST[ "le_activated" ])) {
            $le_activated = 1;
        } else {
            $le_activated = 0;
        }

        
        safe_query(
            "INSERT INTO
                `" . PREFIX . "moduls` (
                    `module`,
                    `le_activated`,
                    `sort`
                )
                VALUES (
                    '$module',
                    '" . $le_activated . "',
                    '1'
                )"
        );
        $id = mysqli_insert_id($_database);

        
    } else {
        echo  $_language->module[ 'transaction_invalid' ];
    }
} elseif (isset($_POST[ 'saveedit' ])) {
    $CAPCLASS = new \webspell\Captcha;
    if ($CAPCLASS->checkCaptcha(0, $_POST[ 'captcha_hash' ])) {
        $module = $_POST[ 'module' ];
        
        if (isset($_POST[ "le_activated" ])) {
            $le_activated = 1;
        } else {
            $le_activated = 0;
        }

        
        $modulID = (int)$_POST[ 'modulID' ];
        $id = $modulID;

        safe_query(
            "UPDATE
                `" . PREFIX . "moduls`
            SET
                `module` = '" . $module . "',
                `le_activated` = '" . $le_activated . "'
            WHERE
                `modulID` = '" . $modulID . "'"
        );

        
    } else {
        echo $_language->module[ 'transaction_invalid' ];
    }
}

$_language->readModule('moduls', false, true);

if (isset($_GET[ 'action' ])) {
    $action = $_GET[ 'action' ];
} else {
    $action = '';
}

if ($action == "add") {
    $CAPCLASS = new \webspell\Captcha;
    $CAPCLASS->createTransaction();
    $hash = $CAPCLASS->getHash();
	
	echo'<div class="panel panel-default">
  <div class="panel-heading">
                            <i class="fa fa-thumbs-up"></i> '.$_language->module['styles'].'
                        </div>
                        </div>
                        <div class="panel panel-default">
                        
            <ul class="nav nav-tabs-primary">    
    <li role="presentation"><a href="/admin/admincenter.php?site=styles">Style</a></li>
    <li role="presentation"><a href="/admin/admincenter.php?site=buttons">Buttons</a></li>
    <li role="presentation" class="active"><a href="/admin/admincenter.php?site=moduls">Module</a></li>
    <li role="presentation"><a href="/admin/admincenter.php?site=css">.css</a></li>
</ul>
<ol class="breadcrumb-primary"> </ol>
	<a href="admincenter.php?site=moduls" class="white">'.$_language->module['module'].'</a> &raquo; '.$_language->module['add_modul'].'<br><br>';

	echo'<form class="form-horizontal" method="post" action="admincenter.php?site=moduls" enctype="multipart/form-data">

     <div class="row">

<div class="col-md-12">

    <div class="form-group">
    <label class="col-sm-2 control-label">'.$_language->module['modul_name'].':</label>
    <div class="col-sm-8"><span class="text-muted small"><em>
      <input type="text" class="form-control" name="module" size="60" /></em></span>
    </div>
  </div>

  <div class="form-group">
    <label class="col-sm-2 control-label">'.$_language->module['left_is_activated'].':</label>
    <div class="col-sm-8"><span class="text-muted small"><em>
      <input type="checkbox" name="le_activated" value="1" checked="checked" /></em></span>
    </div>
  </div>

  
  <div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
		<input type="hidden" name="captcha_hash" value="'.$hash.'" />
		<button class="btn btn-success btn-xs" type="submit" name="save"  />'.$_language->module['add_modul'].'</button>
    </div>
  </div>

</div>
  </div>

  </form></div>
  </div>';
} elseif ($action == "edit") {
    $CAPCLASS = new \webspell\Captcha;
    $CAPCLASS->createTransaction();
    $hash = $CAPCLASS->getHash();
  
  echo'<div class="panel panel-default">
  <div class="panel-heading">
                            <i class="fa fa-thumbs-up"></i> '.$_language->module['styles'].'
                        </div>
                        </div>
                        <div class="panel panel-default">
                        
            <ul class="nav nav-tabs-primary">    
    <li role="presentation"><a href="/admin/admincenter.php?site=styles">Style</a></li>
    <li role="presentation"><a href="/admin/admincenter.php?site=buttons">Buttons</a></li>
    <li role="presentation" class="active"><a href="/admin/admincenter.php?site=moduls">Module</a></li>
    <li role="presentation"><a href="/admin/admincenter.php?site=css">.css</a></li>
</ul>
<ol class="breadcrumb-primary"> </ol>
  <a href="admincenter.php?site=moduls" class="white">'.$_language->module['module'].'</a> &raquo; '.$_language->module['edit_modul'].'<br><br>';
  
  $modulID = $_GET[ 'modulID' ];
    $ergebnis = safe_query("SELECT * FROM " . PREFIX . "moduls WHERE modulID='$modulID'");
    $ds = mysqli_fetch_array($ergebnis);

    if ($ds[ 'le_activated' ] == '1') {
        $le_activated = '<input type="checkbox" name="le_activated" value="1" checked="checked" />';
    } else {
        $le_activated = '<input type="checkbox" name="le_activated" value="1" />';
    }

    echo'<form class="form-horizontal" method="post" action="admincenter.php?site=moduls" enctype="multipart/form-data">

     
<div class="row">

<div class="col-md-12">

    
    <div class="form-group">
    <label class="col-sm-2 control-label">'.$_language->module['modul_name'].':</label>
    <div class="col-sm-8"><span class="text-muted small"><em>
      <input type="text" class="form-control" name="module" value="'.getinput($ds['module']).'" /></em></span>
    </div>
  </div>

  <div class="form-group">
    <label class="col-sm-2 control-label">'.$_language->module['left_is_activated'].':</label>
    <div class="col-sm-8"><span class="text-muted small"><em>
     '.$le_activated.'</em></span>
    </div>
  </div>
  
  <div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
		<input type="hidden" name="captcha_hash" value="'.$hash.'" /><input type="hidden" name="modulID" value="'.$modulID.'" />
		<button class="btn btn-primary btn-xs" type="submit" name="saveedit"  />'.$_language->module['edit_modul'].'</button>
    </div>
  </div>

  </div>
  </div>

  </form></div>
  </div>';
}

else {
	
  echo'<div class="panel panel-default">
  <div class="panel-heading">
                            <i class="fa fa-thumbs-up"></i> '.$_language->module['styles'].'
                        </div>
                        </div>
                        <div class="panel panel-default">
                        
            <ul class="nav nav-tabs-primary">    
    <li role="presentation"><a href="/admin/admincenter.php?site=styles">Style</a></li>
    <li role="presentation"><a href="/admin/admincenter.php?site=buttons">Buttons</a></li>
    <li role="presentation" class="active"><a href="/admin/admincenter.php?site=moduls">Module</a></li>
    <li role="presentation"><a href="/admin/admincenter.php?site=css">.css</a></li>
</ul>
<ol class="breadcrumb-primary"> </ol>
<div class="panel-body">';
  
  echo'<a href="admincenter.php?site=moduls&amp;action=add" class="btn btn-primary btn-xs" type="button">' . $_language->module[ 'new_modul' ] . '</a><br /><br />';

	echo'<form method="post" action="admincenter.php?site=moduls">
  <table class="table table-striped">
    <thead>
      <th><b>'.$_language->module['module'].'</b></th>
      
      <th class="hidden-sm hidden-xs"><b>'.$_language->module['left_is_activated'].'</b></th>
      <th><b>'.$_language->module['actions'].'</b></th>
      <th><b>'.$_language->module['sort'].'</b></th>
    </thead>';

	$moduls = safe_query("SELECT * FROM " . PREFIX . "moduls ORDER BY sort");
    $tmp = mysqli_fetch_assoc(safe_query("SELECT count(modulID) as cnt FROM " . PREFIX . "moduls"));
    $anzmoduls = $tmp[ 'cnt' ];
    $CAPCLASS = new \webspell\Captcha;
    $CAPCLASS->createTransaction();
    $hash = $CAPCLASS->getHash();

    $CAPCLASS->createTransaction();
    $hash_2 = $CAPCLASS->getHash();

    $i = 1;
    while ($db = mysqli_fetch_array($moduls)) {
        if ($i % 2) {
            $td = 'td1';
        } else {
            $td = 'td2';
        }

        $db[ 'le_activated' ] == 1 ? $le_activated = '<font color="green"><b>' . $_language->module[ 'yes' ] . '</b></font>' :
            $le_activated = '<font color="red"><b>' . $_language->module[ 'no' ] . '</b></font>';
        

        

        echo '<tr>
      <td>'.getinput($db['module']).'</td>
      
      <td>'.$le_activated.'</td>
      
      <td><a href="admincenter.php?site=moduls&amp;action=edit&amp;modulID='.$db['modulID'].'" class="hidden-xs hidden-sm btn btn-warning btn-xs" type="button">' . $_language->module[ 'edit' ] . '</a>

        <input class="hidden-xs hidden-sm btn btn-danger btn-xs" type="button" onclick="MM_confirm(\'' . $_language->module['really_delete'] . '\', \'admincenter.php?site=moduls&amp;delete=true&amp;modulID='.$db['modulID'].'&amp;captcha_hash='.$hash.'\')" value="' . $_language->module['delete'] . '" />

	  <a href="admincenter.php?site=moduls&amp;action=edit&amp;modulID='.$db['modulID'].'"  class="mobile visible-xs visible-sm" type="button"><i class="fa fa-pencil"></i></a>
      <a class="mobile visible-xs visible-sm" type="button" onclick="MM_confirm(\'' . $_language->module['really_delete'] . '\', \'admincenter.php?site=moduls&amp;delete=true&amp;modulID='.$db['modulID'].'&amp;captcha_hash='.$hash.'\')" /><i class="fa fa-times"></i></a>


      </td>
      <td>
      <select name="sort[]">';

        for ($j = 1; $j <= $anzmoduls; $j++) {
            if ($db[ 'sort' ] == $j) {
                echo '<option value="' . $db[ 'modulID' ] . '-' . $j . '" selected="selected">' . $j . '</option>';
            } else {
                echo '<option value="' . $db[ 'modulID' ] . '-' . $j . '">' . $j . '</option>';
            }
        }

        echo '</select>
      </td>
    </tr>';
    $i++;
         
	}
	echo'<tr class="td_head">
      <td colspan="5" align="right"><input type="hidden" name="captcha_hash" value="'.$hash_2.'" /><button class="btn btn-primary btn-xs" type="submit" name="sortieren" />'.$_language->module['to_sort'].'</button></td>
    </tr>
  </table>
  </form>';
}
echo '</div></div>';
?>