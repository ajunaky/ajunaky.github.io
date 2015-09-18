<?php 
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    
    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $position = $_POST["pos"];
    $project = trim($_POST["project"]);
    $q1 = trim($_POST["q1"]);
    $q2 = trim($_POST["q2"]);   
    $q3 = trim($_POST["q3"]);
    
    if ($name == "" or $email == "" or $position == "" or $q1 == "" or $q2 == "" or $q3 == "") {
        header("Location: apply.php?status=incomplete");
        exit;
    }
    
    foreach($_POST as $value) {
        if(stripos($value, 'Content-Type:') !== FALSE) {
            header("Location: apply.php?status=spam");
            exit;
        }
    }
    
    if($_POST["address"] != "") {
        header("Location: apply.php?status=spam");
        exit;
    }
    
    require_once("inc/phpMailer/class.phpmailer.php");
    $mail = new PHPMailer();
    
    if (!$mail->ValidateAddress($email)) {
        header("Location: apply.php?status=invalidEmail");
        exit;
    }
    $email_body = "";
    $email_body = $email_body 
    . "<b>" . "Name: " . "</b>" . $name . "<br />" . "<br />" 
    . "<b>" . "Email: " . "</b>" . $email . "<br />" . "<br />" 
    . "<b>" . "Position: " . "</b>" . $position . "<br />" .  "<br />". "<hr />"
    . "<b>" . "Why do you want to join? " . "</b>". $q1 . "<br />" . "<br />" . "<hr />"
    . "<b>" . "What will you bring to the table? " . "</b>" . $q2 . "<br />" . "<br />" . "<hr />"
    . "<b>" . "Random questions? " . "</b>" . $q3;
    $mail->SetFrom($email, $name);
    $address = "appdev@grinnell.edu";
    $mail->AddAddress($address, "Grinnell AppDev");
    $mail->Subject    = "Grinnell AppDev Application Form | " . $name;
    $mail->MsgHTML($email_body);
    if(!$mail->Send()) {
        echo "Mailer Error: " . $mail->ErrorInfo;
        mail($address, $subject, $email_body, $headers);
        exit;
    } 
    header("Location: apply.php?status=thanks");
    exit;
}
?>

<?php 
$activeClass = "apply";
include('inc/header.php'); 
?>

    <link rel="shortcut icon" href="favicon.png">

    <div class="apply">
        <div class="color">
            <div class="apply">
                <div class="container">
                    <div class="page-header main-content">
                        <h1 class="redTitle">You wanna make apps, too?!</h1>

                        <h3 class="redTitle">Roles in AppDev:</h3>

                    <div id="jobOfferings">
                    <div class="col-lg-3">
                    <div class="jobPic" id="devs">
                        <img class="responsive jobs" src="img/developper.png" />
                        <h2 id="developers"><span>Engineering</span></h2>
                        </div>
                    </div>
                    </div>
                    <div class="col-lg-3">
                    <div class="jobPic" id="desi">
                        <img class="responsive jobs" src="img/designer.png">
                        <h2 id="designers"><span>UX &amp; Design</span></h2>
                    </div>
                    </div>
                    <div class="col-lg-3">
                    <div class="jobPic" id="uxd">
                        <img class="responsive jobs" src="img/ux.png">
                        <h2 id="ux"><span>Finance</span></h2>
                        
                    </div>
                    </div>
                    <div class="col-lg-3">
                    <div class="jobPic" id="comm">
                        <img class="responsive jobs" src="img/community.png" />
                        <h2 id="community"><span>Community</span></h2>
                        </div>
                    </div>
                    </div>
                    </div>  
                    
                    <div class="col-lg-12"></div>
            
                                        
                    </div>
                </div>
            </div>
        </div>
      
        <div class="container">

<div class="col-lg-5"></div>
<div class="col-lg-2"><button><a href ="http://grinnellappdev.com/submitapplication.php" style= "font-size:25px; text-align:center; color:#B04F4F;">Apply Here</a></button></div>
<div class="col-lg-5"></div></br></br></br>

        <div class="col-lg-3"></div>
        
        <div class="col-lg-6 jobRole">
                    
        </div>
        
         <div class="col-lg-3"></div>
        
         <div class="col-lg-12"></div>
        
<!--         <div class="col-lg-3"></div>
        <div class="col-lg-6">
            <h2 class="center">Apply!</h2>
            <p class="redTitle center">Applications have closed for the current semester! Check back in the Spring (:</p>
            
            <?php if (isset($_GET["status"]) and $_GET["status"] == "thanks") { ?>
            <p class="redForm">Thanks for applying, we'll be in touch shortly!</p>
            <?php } if (isset($_GET["status"]) and $_GET["status"] == "spam") { ?>
            <p class="redForm">Are you trying to spam us? Shame on you!</p>
            <?php } if (isset($_GET["status"]) and $_GET["status"] == "invalidEmail") { ?>
            <p class="redForm">The email you provided seems to be invalid. Please try again.</p>
            <?php } if (isset($_GET["status"]) and $_GET["status"] == "incomplete") { ?>
            <p class="redForm">Hey, you! You sent us an incomplete form. Shame on you! Please try again.</p> <?php } ?> 
            
            <form class="form" action="apply.php" method="post" id="apply">
            <fieldset>
             
            <label for="name">Name: *</label> <br />
            <input type="text" id="name" name="name"><br />
            <label for="email">Email: *</label> <br />
            <input type="text" id="email" name="email"><br />
            <p><b>Position: *</b></p>
            
            <table>
            <col width="200">
    <tr>
        <td><input type="radio" name="pos" value="Developer"> Developer </td>
        <td><input type="radio" name="pos" value="Designer"> Designer </td>
    </tr>
    <tr>
        <td><input type="radio" name="pos" value="Financial Guru"> Financial Officer </td>
        <td><input type="radio" name="pos" value="Community Builder"> Community Builder </td>
    </tr>
    <tr>
        <td><input type="radio" name="pos" value="Community Builder"> UX Researcher </td>
    </tr>
</table><br/>
            
            <label for="project">Projects/Portfolio: </label> <br />
            <input type="text" id="project" name="project"><br />
            <label for="q1">Why do you want to join?</label> (300 words or less)<br />
            <textarea name="q1" rows="5" cols="80" maxlength="1000" id="q1"></textarea> <br />
            <label for="q2">What will you bring to the table?</label> (300 words or less)<br />
            <textarea name="q2" rows="5" cols="80" maxlength="1000" id="q2"></textarea> <br />
            <label for="q3">What is your favorite website/app and why?</label> (300 words or less)<br />
            <textarea name="q3" rows="5" cols="80" maxlength="1000" id="q3"></textarea> <br /> <br />
            <button onClick = <?php $success = true; ?>type="submit">Submit</button>
            </fieldset>
            </form>
        </div>
        <div class="col-lg-3"></div>   -->

        </div>
        </div>
    </div>

<?php 
    include('inc/footer.php'); 
    ?>
