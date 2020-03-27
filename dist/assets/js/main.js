function submitPost(submitPostBtn) {
  let postForm = $(submitPostBtn).parent();
  $(postForm).one('submit', function(e) {
    e.preventDefault();
    $.post('ajax/submitPost.php', {
      submit: $(submitPostBtn).attr('name'),
      postBody: $('.post-body').val(),
      profile: profile,
      userLoggedIn: userLoggedIn
    }).done(function(data) {
      $('.post-body').val('');
      $('.posts').prepend(data);
    });
  });
}

function deletePost(deletePostBtn) {
  let deletePostForm = $(deletePostBtn).parent();
  let postID = $(deletePostBtn)
    .prev()
    .val();
  let modal = $(deletePostBtn)
    .parent()
    .parent()
    .parent()
    .parent()
    .parent();
  $(deletePostForm).one('submit', function(e) {
    e.preventDefault();
    $.post('ajax/deletePost.php', {
      postID: postID,
      submit: $(deletePostBtn).attr('name'),
      userLoggedIn: userLoggedIn
    }).done(function(data) {
      $(modal).modal('toggle');
      $(`#${postID}`).replaceWith('');
    });
  });
}

function postComment(postCommentBtn) {
  let postForm = $(postCommentBtn)
    .parent()
    .parent()
    .parent();

  let postID = $(postCommentBtn)
    .prev()
    .val();

  let postBody = $(postCommentBtn)
    .parent()
    .parent()
    .prev()
    .find('.comment-input');

  $(postForm).one('submit', function(e) {
    e.preventDefault();
    $.post('ajax/postComment.php', {
      postID: postID,
      postBody: postBody.val(),
      postCommentID: $(postCommentBtn).attr('name'),
      userLoggedIn: userLoggedIn
    }).done(function(data) {
      if (postBody.val()) {
        postBody.val('');
        $(postForm)
          .children('.comment-alert')
          .html("<div class='alert alert-success'>Comment Posted!</div>");

        setTimeout(function() {
          $(postForm)
            .children('.comment-alert')
            .html('');
        }, 3000);
      }

      $(postForm)
        .next()
        .next()
        .next()
        .prepend(data);
    });
  });
}

function likePost(likePostBtn) {
  let postForm = $(likePostBtn)
    .parent()
    .parent()
    .parent();
  let postID = $(likePostBtn)
    .prev()
    .prev()
    .val();

  $(postForm).one('submit', function(e) {
    e.preventDefault();
    $.post('ajax/likePost.php', {
      postID: postID,
      likePostID: $(likePostBtn).attr('name'),
      userLoggedIn: userLoggedIn
    }).done(function(data) {
      $(likePostBtn).html(data);
    });
  });
}

function friendRequest(friendRequestBtn) {
  $('#friend-request-form').one('submit', function(e) {
    e.preventDefault();
    $.post('ajax/friendRequest.php', {
      submit: $(friendRequestBtn).attr('name'),
      profile: profile,
      userLoggedIn: userLoggedIn
    }).done(function(data) {
      $('#friend-request-form').html(data);
    });
  });
}

function respondFR(responseBtn) {
  let form = $(responseBtn).parent();

  let requestID = $(responseBtn)
    .prev()
    .val();

  let submit = $(responseBtn).attr('name');

  let profile = $(responseBtn)
    .parent()
    .prev()
    .prev()
    .find('.profile')
    .attr('href');

  let fullName = $(responseBtn)
    .parent()
    .prev()
    .val();

  let li = $(responseBtn)
    .parent()
    .parent();

  $(form).one('submit', function(e) {
    e.preventDefault();
    $.post('ajax/respondFR.php', {
      requestID: requestID,
      submit: submit,
      profile: profile,
      userLoggedIn: userLoggedIn
    }).done(function(data) {
      $(li).replaceWith(data);
    });
  });
}

function getUsers(value, user) {
  $.post(
    'ajax/messageFriendSearch.php',
    {
      search: value,
      userLoggedIn: user
    },
    function(data) {
      $('.results').html(data);
    }
  );
}
