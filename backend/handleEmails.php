<?php
include "./cors.php";

    require 'vendor/autoload.php';
    
    $router = new AltoRouter();
    
    // Define a route with parameters
    $router->map('GET', '/page/[:emailTo]/[:fullname]/[:subject]/[:year]', function($emailTo, $fullname, $subject, $year) {
        $resetToken = isset($_GET['resetToken']) ? $_GET['resetToken'] : null;
    
        function generateMD5($email) {
            return md5($email);
        }
    
        $encryptedButton = generateMD5($emailTo);
    
        $message = '';
        if ($resetToken) {
            $message = 'Reset token is present.';
        } else {
            $message = 'No reset token provided.';
        }
    
        echo "Email: $emailTo<br>";
        echo "Full Name: $fullname<br>";
        echo "Subject: $subject<br>";
        echo "Year: $year<br>";
        echo "Encrypted Button: $encryptedButton<br>";
        echo "Message: $message<br>";
    });
    
    // Match the current request
    $match = $router->match();
    
    if ($match && is_callable($match['target'])) {
        call_user_func_array($match['target'], $match['params']);
    } else {
        // No route was matched
        header($_SERVER["SERVER_PROTOCOL"] . ' 404 Not Found');
        echo '404 Not Found';
    }
        
    // Function to generate MD5 hash
    function generateMD5($input) {
        return md5($input);
        }
    
    
    //     $email  = req.params["emailTo"];
    //     $fullname = req.params.fullname;
    //     $subject = req.params.subject;
    //     $year = req.params.year;
    //     $encryptedButton = generateMD5(email);
    //     $resetToken = req.query.resetToken;
    //     let message;
    //     if(resetToken){
    //  message = `
    //     <div><img src="https://res.cloudinary.com/dll8awuig/image/upload/v1717282518/raysonFinance_lg8whf.jpg" width=100% alt=www.raysonfinance.org></div>
    //     <h2>Your Password Reset Code is</h2>
    //     <h1>${resetToken}</h1>
    //     <p>Please ignore if this wasn't requested by you</p>
      
    //     <p>(c) ${year} . Rayson Fiance</p>
    //     `
    //     }else{
    //         message = `
    //         <div><img src="https://res.cloudinary.com/dll8awuig/image/upload/v1717282518/raysonFinance_lg8whf.jpg" width=100% alt=www.raysonfinance.org></div>
    //         <h1>Hi there, ${fullname}</h1>
    //         <h2>Thanks For Joining us,</h2>
    //         <p>Please proceed to, verify your email, make a deposit and start earning.</p>
    //         <p><a href=https://www.raysonfinance.org/0auth?email=${email}&verify=${encryptedButton}>
    //         <button style='padding:10px 50px 10px 50px; display:flex; align-self:center; alignt-items:center; justify-self:center; background:dodgerblue; color:white; border:none; outline:none; border-radius:24px; text-align:center;  justfy-content:center;'>
    //         Verify Email
    //         </button></a></p>
    //         <p>(c) ${year} . Rayson Finance</p>`
    //     }
