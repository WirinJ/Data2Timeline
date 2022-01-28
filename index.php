<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Bit Academy Chronoloog</title>
    <link title="timeline-styles" rel="stylesheet" href="https://cdn.knightlab.com/libs/timeline3/latest/css/timeline.css">
    <script src="https://cdn.knightlab.com/libs/timeline3/latest/js/timeline.js"></script>
</head>
<body>
    <div id='timeline-embed' style="width: 100%; height: 600px"></div>
    <script type="text/javascript">
        timeline = new TL.Timeline('timeline-embed',

<?php

include 'classes/Requester.class.php';
$requester = new Requester();

$getAuthCookie = function ($email, $password) use ($requester) {
    $data = json_encode(array("email" => $email, "password" => $password));
    $headers = array('Accept: application/json, text/plain, */*', 'Cookie: userLastLocation=ams.jarvis.bit-academy.nl; XSRF-TOKEN=825d25e4-e5e6-4362-ad2b-f68fd4fb5a00', 'Origin: https://jarvis.bit-academy.nl', 'Referer: https://jarvis.bit-academy.nl/login', 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/96.0.4664.45 Safari/537.36', 'Content-Type: application/json', 'X-XSRF-TOKEN: 825d25e4-e5e6-4362-ad2b-f68fd4fb5a00', 'Accept-Encoding: gzip, deflate, br', 'Accept-Language: nl-NL,nl;q=0.9,en-US;q=0.8,en;q=0.70');
    
    $reqResponse = $requester->POST('https://jarvis.bit-academy.nl/api/v1/authenticate', $data, $headers);
    preg_match('/Authorization=(.*?);/', $reqResponse, $match);
    return "Authorization={$match[1]}";
};

$authCookie = $getAuthCookie("email", "pass");
$splitCookie = explode(".", $authCookie);
$studentData = json_decode(base64_decode($splitCookie[1]), true);

////////////////////// MODULES & EXCERCISES
$rawResponse = $requester->GET('https://ams.jarvis.bit-academy.nl/api/v1/stats/students/' . $studentData["sub"] . '/exercises?limit=10', $authCookie);
$decodedResponse = json_decode($rawResponse, true);

$timelineData = array(
    "title" => array(
        "media" => array(
            "url" => "https://crl2020.imgix.net/wp-content/uploads/2019/04/time-travel-queries-art-JoeRoberts-1.jpg?auto=format,compress&q=60&w=1185",
        ),
        "text" => array(
            "headline" => "JOUW BIT ACADEMY TRAJECT<br/> 2020 - 2021",
            "text" => "<p>Hieronder zie je hoelang je hebt gedaan over elke opdracht, level, module en challenges. Bekijk deze tijdlijn als je je diploma hebt, dat is een mooie herinnering!</p>"
        )
    ),
    "events" => []
);

foreach(array_reverse($decodedResponse["lastExercises"]) as $completedExcercise) {
    // echo '<pre>';
    // var_dump($completedExcercise);
    // echo '</pre>';
    
    $startDate = explode("-", explode("T", $completedExcercise["startedOn"])[0]);
    $endDate = explode("-", explode("T", $completedExcercise["lastStudentAction"])[0]);
    // var_dump($datum);
    $event = array(
        "start_date" => array(
            "year" => $startDate[0],
            "month" => $startDate[1],
            "day" => $startDate[2]
        ),
        "end_date" => array(
            "year" => $endDate[0],
            "month" => $endDate[1],
            "day" => $endDate[2]
        ),
        "text" => array(
            "headline" => $completedExcercise["title"],
            "text" => "Dit is een " . $completedExcercise["type"]
        )
    );

    array_push($timelineData["events"], $event); 
    // break;
}

echo json_encode($timelineData);

?>

        );
    </script>
</body>
</html>
