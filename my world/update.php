<?php
	session_start();
	
	$id =$_SESSION['id'];
	$username =$_SESSION['username'];
	if(!isset($_SESSION['id']))
	{
	header('Location: http://localhost/projj/index.php?error=1');
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
    <head>
        <meta http-equiv="refresh" content="10"/>   
        <style type="text/css">
            .roundtext{
                border:2px solid;
                border-radius:5px;
                -moz-border-radius:25px; /* Firefox 3.6 and earlier */

            }

            .style4 {
                border-style: solid;
                border-width: 1px;
                padding: 1px 4px;
                background-color:#FFFFFF;
                text-align:left;
                border:2px solid;
                border-radius:5px;
                -moz-border-radius:25px; /* Firefox 3.6 and earlier */
                background-image:url('../images/slider-bg.jpg');

            }
            .style5 {
                border-color: inherit;
                border-style: solid;
                border-width: 2px;
                background-color:#5f870e;
                padding-left:100px;
                float:left;
                border-radius:5px;
                -moz-border-radius:25px;
                text-align: center;
                padding-right: 4px;
                padding-top: 1px;
                padding-bottom: 1px;
            }
            .style6 {
                border-style: solid;
                border-width: 1px;
                padding: 1px 4px;
                background-color:#F0F0F0;
                float:right;
                text-align:left;
                padding-left:100px;
                border:2px solid;
                border-radius:5px;
                -moz-border-radius:25px; /* Firefox 3.6 and earlier */

            }

            .style7 {
                font-size:small;
                color: #FF0000;
            }


            .style8 {
                border:2px solid;
                border-radius:5px;
                -moz-border-radius:25px; /* Firefox 3.6 and earlier */
                color:#5F870E;
                text-align: right;
            }


            .style9 {
                text-align: center;
            }


        </style>

    </head>
    <body>

        <form action="postupdate.php" method="post">
            <div>
                <input id="update" name="update" required="required" type="text" placeholder="Post Your Update Here!!!!!!!!" style="width: 408px; height: 26px;" class="roundtext"/>
                <input class="roundtext" type="submit" value="Update Post" style="width: 109px" />
            </div>
        </form>
        <br/>
        <div style="height: 192px;" width="100%" id="posts" >
            <span class="style7"><em>*Note:Updates Auto Refreshes for every 10 seconds</em></span>
            <br/>	

            <?php
            $connection = mysql_connect("localhost", "root", "");

            if (!$connection) {
                die("Database connection failed server might be too busy try again!");
            }

            $dbselect = mysql_select_db("friend", $connection);

            if (!$dbselect) {
                die("Database selection failed server might be too busy try again! ");
            }
            $result = mysql_query("SELECT * FROM  `update` ORDER BY  `id` DESC ", $connection);
            if (!$result) { // if not result die page
                die(" query failed Sorry server might be too busy try again!");
            }
            else {
                while ($row = mysql_fetch_array($result)) {
                    ?>
                    <div class="style4" name="post">
                        <?php
                        echo '<span style="color:#000000">' . $row[1] . '</span>';
                        echo "&nbsp;&nbsp;&nbsp;&nbsp;";
                        echo '<span style="color:#000000">' . $row[2] . '</span>';
                        echo "&nbsp;&nbsp;&nbsp;&nbsp;";
                        echo '<span style="color:#000000">' . $row[3] . '</span>';
                        echo "<br/>";
                        ?>

                        <div name="coment" class="style5" style="width:">
                            <?php
                            $cresult = mysql_query("SELECT * FROM  `updatecmnt` WHERE  `updateid` =$row[0] ", $connection);
                            if (!$cresult) { // if not result die page
                                die(" query failed Sorry server might be too busy try again!");
                            }
                            else {
                                if ($cresult) {
                                    echo "Write your comment on this post";
                                    echo "<br/>";
                                }
                                while ($crow = mysql_fetch_array($cresult)) {
                                    ?>
                                    <div name="coments" class="style6" style="width: 442px" >
                                        <?php
                                        echo '<span style="color:#000000">' . $crow[1] . '</span>';
                                        echo "&nbsp;&nbsp;&nbsp;&nbsp;";
                                        echo '<span style="color:#000000">' . $crow[2] . '</span>';
                                        echo "&nbsp;&nbsp;&nbsp;&nbsp;";
                                        echo '<span style="color:#000000">' . $crow[3] . '</span>';
                                        echo "&nbsp;&nbsp;&nbsp;&nbsp;";
                                        echo '<span style="color:#000000">' . $crow[4] . '</span>';
                                        ?>
                                        <div class="style9" style="background-image:url('../images/slider-bg.jpg')">
                                            <input name="likebtn" type="button" value="" class="style8" style="background-image:url('../images/like.png'); float:right; cursor:pointer; width: 51px; height: 34px;"/></div>
                                    </div>

                <?php
                echo "<br/>";
            }
        }
        ?>
                        </div>                    
                        <br/>       <form method="post" action="postcoment.php?">
                            <input id="cmnt" name="cmnt" required="required" type="text" placeholder="Post Your coment Here!!!!!!!!" style="width: 408px; height: 17px;" class="roundtext"/></form>
                    </div>


                    <hr />
                    <br/>
                    <?php
                }
            }
            ?>
        </div>
    </body>
</html>
