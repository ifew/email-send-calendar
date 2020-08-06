<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
use Kigkonsult\Icalcreator\Vcalendar;

require 'vendor/autoload.php';

$mail = new PHPMailer(true);

try {
    // create a new calendar
    $vcalendar = Vcalendar::factory( [ Vcalendar::UNIQUE_ID => "myifew.com", ] )
    ->setMethod( Vcalendar::REQUEST )
    ->setXprop(
        Vcalendar::X_WR_CALNAME,
        "นัดหมาย กินชาบู"
    )
    ->setXprop(
        Vcalendar::X_WR_CALDESC,
        "ปฏิทินนัดหมาย กินชาบู"
    )
    ->setXprop(
        Vcalendar::X_WR_RELCALID,
        "1234"
    )
    ->setXprop(
        Vcalendar::X_WR_TIMEZONE,
        "Asia/Bangkok"
    );

    $event1 = $vcalendar->newVevent()
    ->setTransp( Vcalendar::OPAQUE )
    ->setClass( Vcalendar::P_BLIC )
    ->setSequence( 1 )
    ->setSummary( 'ปาร์ตี้ชาบู' )
    ->setDescription(
        'เป็นบุฟเฟ่นะ อยากสั่งอะไรก็สั่ง'
   )
    // place the event
    ->setLocation( 'ร้านไหนสักแห่ง' )
    // ->setGeo( '59.32206', '18.12485' )
    // set the time
    ->setDtstart(
        new DateTime(
        '20200810T080000',
        new DateTimezone( 'Asia/Bangkok' )
        )
    )
    ->setDtend(
        new DateTime(
        '20200814T170000',
        new DateTimezone( 'Asia/Bangkok' )
        )
    )
    ->setOrganizer(
        'chitpong@myifew.com',
        [ Vcalendar::CN => 'chitpong@myifew.com' ]
    )
    ->setAttendee(
        'recipient@mail.com',
        [
            Vcalendar::ROLE     => Vcalendar::CHAIR,
            Vcalendar::PARTSTAT => Vcalendar::ACCEPTED,
            Vcalendar::RSVP     => Vcalendar::FALSE,
            Vcalendar::CN       => 'recipient@mail.com',
        ]
        );

    $vcalendar->setComponent($event1);

    // add alarm for the event
    $alarm = $event1->newValarm()
    ->setAction( Vcalendar::DISPLAY )
    ->setDescription( $event1->getDescription())
    // fire off the alarm one day before
    ->setTrigger( '-P1D' );

    $vcalendarString = $vcalendar->vtimezonePopulate()->createCalendar();


    //Server settings
    $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      // Enable verbose debug output
    $mail->isSMTP();                                            // Send using SMTP
    $mail->Host       = 'smtp.gmail.com';                    // Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
    $mail->Username   = 'chitpong@myifew.com';                     // SMTP username
    $mail->Password   = '1234';                               // SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
    $mail->Port       = 587;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

    //Recipients
    $mail->setFrom('chitpong@myifew.com', 'Chitpong Wuttanan');
    $mail->addAddress('recipient@mail.com', 'Recipient');     // Add a recipient
    $mail->AddStringAttachment($vcalendarString, "ical.ics", "base64", "text/calendar; charset=utf-8; method=REQUEST");
    //$mail->Ical = $vcalendarString;

    // Content
    $mail->isHTML(true);                                  // Set email format to HTML
    $mail->Subject = '=?utf-8?b?'.base64_encode('นัดกินชาบูกันนะ').'?=';
    $mail->Body    = 'ทดสอบๆ ลองดูในปฎิทินหน่อยว่ามีสร้าง calendar ไหม</b>';

    $mail->send();
    echo 'Message has been sent';
    echo $vcalendarString;
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}