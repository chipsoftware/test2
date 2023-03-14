<?php
// видаляємо з БД дані категорій
$sql = "DELETE FROM `category`";
$result = $conn->query($sql);

// видаляємо з БД дані проєктів
$sql = "DELETE FROM `projects`";
$result = $conn->query($sql);

// Отримання даних з API
$url = 'https://api.freelancehunt.com/v2/projects';

$headers = array(
	'Authorization: Bearer '.$freelancehuntToken
);
	
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
$response = curl_exec($ch);
curl_close($ch);

// Перетворення відповіді в масив
if ($response) {
	$data = json_decode($response, true);
} 	
	
// Збереження даних в базі даних
if (isset($data['data'])) {
	foreach ($data['data'] as $project) {
		$id = $conn->real_escape_string($project['id']);
		$name = $conn->real_escape_string($project['attributes']['name']);
		$budget = $conn->real_escape_string($project['attributes']['budget']['amount']);
		$status = $conn->real_escape_string($project['attributes']['status']['id']);
		$link = $conn->real_escape_string($project['links']['self']['web']);
		$employer_login = $conn->real_escape_string($project['attributes']['employer']['login']);
		$employer_first_name = $conn->real_escape_string($project['attributes']['employer']['first_name']);
		$employer_last_name = $conn->real_escape_string($project['attributes']['employer']['last_name']);
		
		$skills = $project['attributes']['skills'];
		
		$budget = intval($budget) !=0 ? intval($budget) : 0;
		
		$query = "INSERT INTO `projects` (`id`, `name`, `budget`, `link`, `status`, `employer_login`, `employer_first_name`, `employer_last_name`) 
			VALUES ('$id', '$name', '$budget', '$link', '$status', '$employer_login', '$employer_first_name', '$employer_last_name')";
		$result = $conn->query($query);
		
		if ($result) {
			$sql = array();
			foreach ($skills as $skill) {
				$skill_id = $conn->real_escape_string($skill['id']);
				$sql[] = "('$id', '$skill_id')";
			}	
			$query = "INSERT INTO `category` (`project`, `skill`) 
				VALUES ".implode(", ", $sql);
			$result = $conn->query($query);
		}	
	}
}	

?>