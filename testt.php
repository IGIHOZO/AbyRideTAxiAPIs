<?php 
    $sender = 'ddrigihozo@gmail.com';
    $recipient = 'didierigihozo07@gmail.com';

    $subject = " TAXI DRIVER TEST EMAIL";
    $message = '
<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xmlns:o="urn:schemas-microsoft-com:office:office">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <meta name="x-apple-disable-message-reformatting">
  <title></title>
</head>
<body style="margin:0;padding:0;word-spacing:normal;background-color:#37517E;">
  <div role="article" aria-roledescription="email" lang="en" style="text-size-adjust:100%;-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%;background-color:#37517E;">
    <table role="presentation" style="width:100%;border:none;border-spacing:0;">
      <tr>
        <td align="center" style="padding:0;">
          <table role="presentation" style="width:94%;max-width:600px;border:none;border-spacing:0;text-align:left;font-family:Arial,sans-serif;font-size:16px;line-height:22px;color:#363636;">
            <tr>
              <td style="padding:40px 30px 30px 30px;text-align:center;font-size:24px;font-weight:bold;">
                <a href="http://www.abyride.com/" style="text-decoration:none;"><img src="https://abyride.com/assets/img/logo.jpeg" width="165" alt="Logo" style="width:165px;max-width:80%;height:auto;border:none;text-decoration:none;color:#ffffff;"></a>
              </td>
            </tr>
            <tr>
              <td style="padding:30px;background-color:#ffffff;">
                <h1 style="margin-top:0;margin-bottom:16px;font-size:26px;line-height:32px;font-weight:bold;letter-spacing:-0.02em;">Welcome to AbyRide-TAXI Drivers Portal !</h1>
                <p style="margin:0;">To become an AbyRide-Taxi Partner, we ask you to submit some required document to make sure you are eligible for this action. <a href="https://taxi.abyride.com/driver_upload.php" style="color:#e50d70;text-decoration:underline;font-weight: bold;">Click here</a> to upload required documents . Once you are done we will get back to you after verifying them</p>
              </td>
            </tr>
            <tr>
              <td style="padding:0;font-size:24px;line-height:28px;font-weight:bold;">
                <a href="http://www.example.com/" style="text-decoration:none;"><img src="https://taxi.abyride.com/assets/imgs/driver.jpg" width="600" alt="" style="width:100%;height:auto;display:block;border:none;text-decoration:none;color:#363636;"></a>
              </td>
            </tr>
            <tr>
              <td style="padding:35px 30px 11px 30px;font-size:0;background-color:#ffffff;border-bottom:1px solid #f0f0f5;border-color:rgba(201,201,207,.35);">

                <div class="col-sml" style="display:inline-block;width:100%;max-width:145px;vertical-align:top;text-align:left;font-family:Arial,sans-serif;font-size:14px;color:#363636;">
                  <img src="https://abyride.com/assets/img/logo.jpeg" width="115" alt="" style="width:115px;max-width:80%;margin-bottom:20px;">
                </div>
                <div class="col-lge" style="display:inline-block;width:100%;max-width:395px;vertical-align:top;padding-bottom:20px;font-family:Arial,sans-serif;font-size:16px;line-height:22px;color:#363636;">
                  <p style="margin-top:0;margin-bottom:18px;">You can make their own customer requests, noble deeds reward points, can ba highly recommended, you will be given daily summary report for all the income and commission.</p>
                  <p style="margin-top:0;margin-bottom:12px;">To be authorosed as a driver of AbyRide-TAXI, you have to submit your driver licence, Vehicle Licence and Insurance documents. so by clicking the below button: you will be allowed to submit their photos.</p>

                  <p style="margin:0;"><a href="https://taxi.abyride.com/driver_upload.php" style="background: #ff3884; text-decoration: none; padding: 10px 25px; color: #ffffff; border-radius: 4px; display:inline-block; mso-padding-alt:0;text-underline-color:#ff3884"><span style="mso-text-raise:10pt;font-weight:bold;">Proceed with registration</span></a></p>
                </div>
              </td>
            </tr>
            <tr>
              </td>
            </tr>
            <tr>
              <td style="padding:30px;text-align:center;font-size:12px;background-color:#404040;color:#cccccc;">
                <p style="margin:0 0 8px 0;"><a href="http://www.facebook.com/" style="text-decoration:none;"><img src="https://assets.codepen.io/210284/facebook_1.png" width="40" height="40" alt="f" style="display:inline-block;color:#cccccc;"></a> <a href="http://www.twitter.com" style="text-decoration:none;"><img src="https://assets.codepen.io/210284/twitter_1.png" width="40" height="40" alt="t" style="display:inline-block;color:#cccccc;"></a></p>
                <p style="margin:0;font-size:14px;line-height:20px;">&reg; AbyRide, 2022  *  +1 (616) 633-7026  *  abyride.com/<br><a class="unsub" href="https://abyride.com/" style="color:#cccccc;text-decoration:underline;">Grand Rapids – Kalamazoo – Lansing
31 Buckingham ST SW Grand Rapids, MI49548
United States</a></p>
              </td>
            </tr>
          </table>
        </td>
      </tr>
    </table>
  </div>
</body>
</html>
';
        $headers  = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";


        $headers .= 'From: ' .$sender. "\r\n";


    if (mail($recipient, $subject, $message, $headers))
    {
        echo "accepted";
    }
    else
    {
        echo "not accepted";
    }
?>