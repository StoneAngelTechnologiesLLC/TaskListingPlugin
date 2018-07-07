<?php
/**
 * The template for displaying all single posts and attachments
 *
 * @package WordPress
 * @subpackage Twenty_Fifteen
 * @since Twenty Fifteen 1.0
 */
//This is simple fetching post meta
$task_fetch_meta = get_post_meta( get_the_ID() );
var_dump( $task_fetch_meta );
echo '<br />';
$task1 = get_post_meta( get_the_ID(), 'date_listed', true );
var_dump($task1);
echo '<br />';
$task2 = get_post_meta( get_the_ID(), 'application_deadline', true );
var_dump($task2);
echo '<br />';
$task3 = get_post_meta( get_the_ID(), 'minimum_requirements', true );
var_dump($task3);
