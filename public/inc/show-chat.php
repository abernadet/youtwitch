<?php
$select = $bdd->query('SELECT * FROM chat ORDER BY date_envoie DESC');
$chats = $select->fetchAll();
foreach ($chats as $chat) {
    $date_envoie = new DateTime($chat['date_envoie']);
}
$json = '';
foreach($chats as $chat){
    $json.= '<p>' . $chat['message'] . '</p>';
    $json.= '<p>Publié par ' . $chat['pseudo'] . ' le ' . $date_envoie->format('d/m/Y') . '</p><hr>';
}

echo json_encode($json);

?>