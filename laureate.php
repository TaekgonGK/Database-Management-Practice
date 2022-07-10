<?php
// get the id parameter from the request
$id = intval($_GET['id']);

// set the Content-Type header to JSON, 
// so that the client knows that we are returning JSON data
header('Content-Type: application/json');

$db = new mysqli('localhost', 'cs143', '', 'class_db');
if ($db->connect_errno > 0) { 
    die('Unable to connect to database [' . $db->connect_error . ']'); 
}

$id = $_GET["id"];

$query = "SELECT * FROM Person WHERE id = $id";
$rs = $db->query($query);
$row = $rs->fetch_assoc();

$givenName = $row['givenName'];
$familyName = $row['familyName'];
$gender = $row['gender'];

$query = "SELECT * FROM WhenWhere WHERE id = $id";
$rs = $db->query($query);
$row = $rs->fetch_assoc();

$date = $row['date'];
$city = $row['city'];
$country = $row['country'];

$query = "SELECT * FROM Prize WHERE id = $id";
$rs_parent = $db->query($query);

$nobPrizes = [];
$counter = 0;

if ($rs_parent->num_rows > 1) {
    while ($row_parent = $rs_parent->fetch_assoc()) {
        $nobelnumber = $row_parent['nobelnumber'];
        $sortOrder = $row_parent['sortOrder'];

        $aff_Name = $row_parent['affiliation'];

        $query = "SELECT * FROM Affiliation WHERE name = '%s'";
        $query_to_issue = sprintf($query,$aff_Name);
        $rs = $db->query($query_to_issue);
        $row = $rs->fetch_assoc();

        $affName = $row['name'];
        $affCity = $row['city'];
        $affCountry = $row['country'];

        $query = "SELECT * FROM Nobel WHERE nobelnumber = '%s'";
        $query_to_issue = sprintf($query, $nobelnumber);
        $rs = $db->query($query_to_issue);
        $row = $rs->fetch_assoc();

        $awardYear = $row['awardYear'];
        $category = $row['category'];

        if(!is_null($row['city'])){
            $nobPrizes = (object)[
                "awardYear" => $awardYear,
                "category" => (object) ["en" => $category],
                "sortOrder" => $sortOrder,
                "affiliations" => array(
                    (object) [
                        "name" => (object) ["en" => $affName],
                        "city" => (object) ["en" => $affCity],
                        "country" => (object) ["en" => $affCountry]
                    ]
                )
            ];
    
        }
        else{
            $nobPrizes = (object)[
                "awardYear" => $awardYear,
                "category" => (object) ["en" => $category],
                "sortOrder" => $sortOrder
            ];
    
        }
        $nobPrizes = array_merge($nobPrizes, array($nobPrize));
        $counter = $counter + 1;
    }
}

else {
    $row = $rs_parent->fetch_assoc();
    $nobelnumber = $row['nobelnumber'];
    $sortOrder = $row['sortOrder'];
    $aff_Name = $row['affiliation'];
    

    $query = "SELECT * FROM Affiliation WHERE name = '%s'";
    $query_to_issue = sprintf($query,$aff_Name);
    $rs = $db->query($query_to_issue);
    $row = $rs->fetch_assoc();

    $affName = $row['name'];
    $affCity = $row['city'];
    $affCountry = $row['country'];

    $query = "SELECT * FROM Nobel WHERE nobelnumber = '%s'";
    $query_to_issue = sprintf($query, $nobelnumber);
    $rs = $db->query($query_to_issue);
    $row = $rs->fetch_assoc();

    $awardYear = $row['awardYear'];
    $category = $row['category'];

    if(!is_null($affName)){
        $nobPrizes = (object)[
            "awardYear" => $awardYear,
            "category" => (object) ["en" => $category],
            "sortOrder" => $sortOrder,
            "affiliations" => array(
                (object) [
                    "name" => (object) ["en" => $affName],
                    "city" => (object) ["en" => $affCity],
                    "country" => (object) ["en" => $affCountry]
                ]
            )
        ];

    }
    else{
        $nobPrizes = (object)[
            "awardYear" => $awardYear,
            "category" => (object) ["en" => $category],
            "sortOrder" => $sortOrder
        ];

    }
    
}

if(!is_null($gender))
{
    if(!is_null($city)){
        $output = (object) [
            "id" => strval($id),
            "givenName" => (object) [
                "en" => $givenName,
            ],
            "familyName" => (object) [
                "en" => $familyName
            ],
            "gender" => $gender,
            "birth" => (object) [
                "date" => $date,
                "place" => (object) [
                    "city" => (object) ["en" => $city],
                    "country" => (object) ["en" => $country]
                ]
            ],
            "nobelPrizes" => array($nobPrizes)
            
        ];
    }
    else{
        $output = (object) [
            "id" => strval($id),
            "givenName" => (object) [
                "en" => $givenName,
            ],
            "familyName" => (object) [
                "en" => $familyName
            ],
            "gender" => $gender,
            "birth" => (object) [
                "date" => $date
            ],
            "nobelPrizes" => array($nobPrizes)
            
        ];
    }
    
}

else{
    if(!is_null($city)){
        $output = (object) [
            "id" => strval($id),
            "orgName" => (object) [
                "en" => $givenName,
            ], 
            "founded" => (object) [
                "date" => $date,
                "place" => (object) [
                    "city" => (object) ["en" => $city],
                    "country" => (object) ["en" => $country]
                ]
            ],
            "nobelPrizes" => array($nobPrizes),
            
        ];
    }
    else{
        $output = (object) [
            "id" => strval($id),
            "orgName" => (object) [
                "en" => $givenName,
            ], 
            "founded" => (object) [
                "date" => $date
            ],
            "nobelPrizes" => array($nobPrizes),
            "orgName" => (object) ["en" => $givenName]
        ];
    }
    
}

echo json_encode($output, JSON_PRETTY_PRINT);
$rs->free();
$db->close();
?>