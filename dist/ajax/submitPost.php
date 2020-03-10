<?php

require('../includes/config.php');
require('../includes/classes/User.php');
require('../includes/classes/Post.php');

$userLoggedIn = $_POST['userLoggedIn'];
$profile = $_POST['profile'];
$body = $_POST['postBody'];
$user = new User($con, $userLoggedIn);
$post = new Post($con, $userLoggedIn);

if (isset($_POST['submit'])) {
  $body = strip_tags($body);
  $body = str_replace('\r\n', '\n', $body);
  $body = nl2br($body);

  $checkEmpty = preg_replace('/\s+/', '', $body);

  if ($checkEmpty != "") {

    $addedBy = $userLoggedIn;

    if ($profile == $addedBy) {
      $userTo = "none";
    } else {
      $userTo = $profile;
    }

    $dateAdded = date("Y-m-d H:i:s");
    $likes = 0;

    $query = $con->prepare("INSERT INTO posts (body, added_by, user_to, date_added, user_closed, deleted, likes) VALUES(:body, :addedBy, :userTo, :dateAdded, :userClosed, :deleted, :likes)");
    $query->execute([
      ':body' => $body,
      ':addedBy' => $addedBy,
      ':userTo' => $userTo,
      ':dateAdded' => $dateAdded,
      ':userClosed' => 'no',
      ':deleted' => 'no',
      ':likes' => $likes
    ]);
    $id = $con->lastInsertId();

    $numPosts = $user->getPostCount();
    $numPosts++;

    $postCountQuery = $con->prepare("UPDATE users SET num_posts = :numPosts WHERE username = :un");
    $postCountQuery->execute([
      ':numPosts' => $numPosts,
      ':un' => $user->getUsername()
    ]);

    echo "
      <div id='$id' class='card post my-4'>
      
        <div class='card-header'>
          <div class='media'>
            <div class='post-profile-pic pr-2'>
              <img src='" . $user->getProfilePic() . "' class='img-fluid rounded-circle'>
            </div>
            <div class='media-body'>
              <div class='posted-by'>
                <a href='$addedBy'>" . $user->getFullName() . "</a>
                <small class='d-block'>" . $post->getDate($dateAdded) . "</small> 
              </div>
            </div>
          </div>
          " . $post->deletePostBtn($id) . "
        </div>

        <div class='card-body'>
          <div class='post-body pb-4'>
            $body
          </div>

            <form id='comment-form-$id' class='my-3'>

                <span class='comment-alert'></span>
                
                <div class='form-group'>
                  <div class='media'>
                      <img src='" . $user->getProfilePic() . "' class='img-fluid comment-profile-pic' alt='" . $user->getFullName() . "'>
                    <div class='media-body'>
                      <input type='text' name='post-body-$id' class='form-control comment-input' placeholder='Write a comment...'>
                    </div>
                  </div>
                </div>

                <div class='form-group'>

                  <div class='btn-group comment-like-btns'>      
                    <input type='hidden' value='$id'>

                    <button onclick='postComment(this)' name='post-comment-$id' class='btn btn-outline-secondary'>
                    <i class='far fa-comment-alt'></i> Comment
                    </button>

                    <button onclick='likeStatus(this)' name='like-status-$id' class='btn btn-outline-secondary'>

                      " . $post->displayLikeBtn($id) . "
                      
                    </button>
                  </div>

                </div>

            </form>

            <hr>

            <p class='post-stats'>" . $post->getCommentCount($id) . ", " . $post->getLikeCount($id) . "</p>

            <div class='comments'>
              
              " . $post->loadComments($id) . "

            </div>

        </div>

      </div>

      <div class='modal fade' id='delete-post-modal-$id' tabindex='-1' role='dialog' aria-hidden='true'>
        <div class='modal-dialog' role='document'>
          <div class='modal-content'>
            <div class='modal-header'>
              <h5 class='modal-title'>
                Delete Post?
              </h5>
              <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                <span aria-hidden='true'>&times;</span>
              </button>
            </div>
            <div class='modal-body'>
              Are you sure you want to delete this post?
            </div>
            <div class='modal-footer'>
              <form id='delete-post-form-$id'>
                <button type='button' class='btn btn-outline-dark' data-dismiss='modal'>Cancel</button>
                <input type='hidden' value='$id'>
                <button onclick='deletePost(this)' type='submit' class='btn btn-primary' name='delete-post-$id'>Confirm</button>
              </form>
            </div>
          </div>
        </div>
      </div>
    ";
  }
}
