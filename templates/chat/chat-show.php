<?php
$json = '';
foreach($chats as $chat){
$json.= '<p>' . $chat['message'] . '</p>';
$json.= '<p>Publié par ' . $chat['user'] . ' le ' . $date_envoie->format('d/m/Y') . '</p><hr>';
}

echo json_encode($json);