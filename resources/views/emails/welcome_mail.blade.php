<?php ?>

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Tirtheasy</title>
</head>
@php
date_default_timezone_set('Asia/Kolkata');
$siteUrl = "https://tirtheasy.com/";
@endphp
<body bgcolor="#f7f8fa">
    <div style="width:100%;" align="center">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tbody>
<tr>
<td align="center" valign="top">
<table width="800" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff" style="background-color:#ffffff; padding:15px">
    <tbody>
        <tr>
            <td align="center" valign="top" style="color:#000000; font-family: PT Sans; border: 1px solid #484848; font-size:16px;padding: 28px 18px 0;-webkit-font-smoothing: antialiased;">
                <table width="100%" border="0" cellspacing="0" cellpadding="0" style="border-bottom: solid 6px #cc484a;padding-bottom: 25px;">
                    <tbody>
                        <tr>
                            <td align="left" valign="middle">
                                <h2 style="margin:0px; font-family:Tahoma, Geneva, sans-serif"><span ><img src="{{$siteUrl}}public/img/a-logo.png" alt="tirtheasy" title="tirtheasy" style="width:138px" /></h2>
                            </td>
                            <td align="right" valign="middle" style="color: #000000;font-size: 14px;font-weight: 300;font-family:Tahoma, Geneva, sans-serif">
                                <b style="color: #000000; font-weight: bold;">Date:</b> {{date('d M, Y h:i A')}}
                            </td>
                        </tr>
                    </tbody>
                </table>

                <table width="100%" border="0" cellspacing="0" cellpadding="0" style="padding-top: 20px; padding-bottom: 15px;">
                    <tbody>
                        <tr>
                            <td align="left" valign="middle" style="color: #000;font-size: 13px;font-weight: 550; padding-top: 10px;font-family:Tahoma, Geneva, sans-serif;-webkit-font-smoothing: antialiased;">
                                Dear {{$record->name}},
                            <td>
                        </tr>
                        <tr>
                            <td align="left" valign="middle" style="color: #000;font-size: 12px;font-weight: normal; padding-top: 10px;font-family:Tahoma, Geneva, sans-serif;-webkit-font-smoothing: antialiased;">
                                Thank you for signing up with TirthEasy – your trusted partner for seamless Dharmshala bookings across India’s top religious destinations.<br /><br />
                                We’re excited to have you onboard!<br /><br />
                                Whether you’re planning a solo pilgrimage, a family yatra, or a community trip, we’re building a platform that ensures affordable, verified, and easy-to-book dharmshala stays, all in one place.<br /><br />
                                What to expect next?
                                <ul style="margin-left:0px; padding-left:0px">
                                    <li>Priority access to our launch</li>
                                    <li>Early bird offers exclusively for you</li>
                                    <li>Updates on new destinations and dharmshala listings</li>
                                    <li>Tools to help you plan your yatra hassle-free</li>
                                </ul>
                                <br />
                                Stay tuned – we’ll be launching soon, and you’ll be the first to know!<br /><br />
                                If you have any questions, suggestions, or destinations you'd love us to cover, reply to this mail. We’d love to hear from you.
                            <td>
                        </tr>

                        <tr>
                        <td style="font-size: 14px;font-weight: 300; color: #000000;font-family:Tahoma, Geneva, sans-serif;-webkit-font-smoothing: antialiased;    line-height: 20px;">
                        <br />
                            <table style="display:none;width: 100%;border: 1px solid #484848;font-family:Tahoma, Geneva, sans-serif;border-collapse: collapse;border-radius: 5px;border-style: hidden;box-shadow: 0 0 0 1px #484848;-webkit-font-smoothing: antialiased;">
                                <tr style="border-bottom: 1px solid #484848;">
                                    <td style="padding: 10px; color: #484848; font-size: 14px; padding-left: 5px;">User Name:</td>
                                    <td style="padding: 10px; color: #484848; font-size: 14px;">{{$record['name']}}</td>
                                </tr>
                                <tr style="border-bottom: 1px solid #484848;">
                                    <td style="padding: 10px; color: #484848; font-size: 14px; padding-left: 5px;">Email Address:</td>
                                    <td style="padding: 10px; color: #484848; font-size: 14px;">{{$record['email']}}</td>
                                </tr>
                                @if(!empty($record['mobile']))
                                <tr style="border-bottom: 1px solid #484848;">
                                    <td style="padding: 10px; color: #484848; font-size: 14px; padding-left: 5px;">Mobile Number:</td>
                                    <td style="padding: 10px; color: #484848; font-size: 14px;">{{$record['mobile']}}</td>
                                </tr>
                                @endif
                            </table>
                            <br /><br />
						    Thank you,<br />
                            Tirtheasy Team<br />
                            <a href="https://tirtheasy.com"><img src="{{$siteUrl}}public/img/web.png" style="width:25px" /></a>
                            <a href="https://www.linkedin.com/company/dharmshala/" target="_blank"><img src="{{$siteUrl}}public/img/linkedin.png" style="width:25px"/></a>
                            <a href="https://www.instagram.com/tirtheasy?igsh=bTYzd3RudjJwb2hr" target="_blank"><img src="{{$siteUrl}}public/img/instagram.png" style="width:25px"/></a>
                            <a href="https://x.com/Tirtheasy?s=08" target="_blank"><img src="{{$siteUrl}}public/img/twitter.png" style="width:25px"/></a>
                            <a href="https://youtube.com/@tirtheasy?si=klXyQkRLuCOEO4OD" target="_blank"><img src="{{$siteUrl}}public/img/youtube.png" style="width:25px"/></a>
                            <a href="https://www.facebook.com/share/1CJkWk9nrB/" target="_blank"><img src="{{$siteUrl}}public/img/facebook.png" style="width:25px"/></a>                        
                        </td>
                        </tr>
                    </tbody>
                </table>
                <table width="100%" border="0" cellspacing="0" cellpadding="0" >
                    <tr>
                        <td align="center" valign="middle" bgcolor="#484848" style="background: #484848; color: #ffffff;font-size: 14px;font-weight: 300;font-family: Tahoma, Geneva, sans-serif;line-height: 26px;padding: 3px 0 3px 0; ">
                             Copyright © {{date('Y')}} -Tirtheasy All Rights Reserved.
                        </td>
                    </tr>
                     <tr><td>&nbsp;</td></tr>
                </table>
            </td>
        </tr>
    </tbody>
</table>
</td>
</tr>
</tbody>
</table>

</div>
</body>
</html>
