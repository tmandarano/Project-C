<?php
require_once('src/models/tag.php');
require_once('src/dao/tag_dao.php');

function tags_get() {
    $tags = TagDAO::get_tags();
    return json($tags);
}

function tags_get_by_id() {
    $tag = TagDAO::get_tag_by_id(var_to_i(params('id')));
    return json($tag);
}

function tags_get_by_user_id() {
    check_system_auth();

    $user_id = var_to_i(params('id'));
    $tags = TagDAO::get_tags_by_user_id($user_id);
    return json($tags);
}

function tags_get_by_user_id_recent() {
    check_system_auth();

    $user_id = var_to_i(params('id'));
    $days = var_to_i(params('days'));
    $tags = TagDAO::get_tags_by_user_id_recent($user_id, $days);
    return json($tags);
}

function tags_recent() {
    $limit = var_to_i(params('limit'));
    return json(TagDAO::get_recent_tags($limit));
}

function tags_trending() {
    $limit = var_to_i(params('limit'));
    return json(TagDAO::get_trending_tags($limit));
}
?>
