<?php


class Post
{
  private $con, $user;

  public function __construct($con, $email)
  {
    $this->con = $con;
    $this->user = new User($con, $email);;
  }
  public function submitPost($body, $userTo)
  {
    $body = strip_tags($body);
    $body = str_replace('\r\n', '\n', $body);
    $body = nl2br($body);

    $checkEmpty = preg_replace('/\s+/', '', $body);

    if ($checkEmpty != "") {

      $addedBy = $this->user->getUsername();

      if ($userTo == $addedBy) {
        $userTo = "none";
      }

      $dateAdded = date("Y-m-d H:i:s");
      $userClosed = 'no';
      $deleted = 'no';
      $likes = 0;

      $query = $this->con->prepare("INSERT INTO posts (body, added_by, user_to, date_added, user_closed, deleted, likes) VALUES(:body, :addedBy, :userTo, :dateAdded, :userClosed, :deleted, :likes)");
      $query->execute([
        ':body' => $body,
        ':addedBy' => $addedBy,
        ':userTo' => $userTo,
        ':dateAdded' => $dateAdded,
        ':userClosed' => $userClosed,
        ':deleted' => $deleted,
        ':likes' => $likes
      ]);
      $returnedID = $this->con->lastInsertId();

      $numPosts = $this->user->getPostCount();
      $numPosts++;

      $postCountQuery = $this->con->prepare("UPDATE users SET num_posts = :numPosts WHERE username = :un");
      $postCountQuery->execute([
        ':numPosts' => $numPosts,
        ':un' => $this->user->getUsername()
      ]);
    }
  }
}
