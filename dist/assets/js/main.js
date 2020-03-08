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

function likeStatus(likeStatusBtn) {
  let postForm = $(likeStatusBtn)
    .parent()
    .parent()
    .parent();
  let postID = $(likeStatusBtn)
    .prev()
    .prev()
    .val();

  $(postForm).one('submit', function(e) {
    e.preventDefault();
    $.post('ajax/likeStatus.php', {
      postID: postID,
      likeStatusID: $(likeStatusBtn).attr('name'),
      userLoggedIn: userLoggedIn
    }).done(function(data) {
      $(likeStatusBtn).html(data);
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

function wallPost(wallPostBtn) {
  $('#wall-post-form').one('submit', function(e) {
    e.preventDefault();
    $.post('ajax/wallpost.php', {
      submit: $(wallPostBtn).attr('name'),
      postBody: $('.wall-post-body').val(),
      profile: profile,
      userLoggedIn: userLoggedIn
    }).done(function() {
      $('#post-modal').modal('toggle');
      $('.wall-post-body').val('');
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
    .attr('href');
  let fullName = $(responseBtn)
    .parent()
    .prev()
    .text();
  let card = $(responseBtn)
    .parent()
    .parent()
    .parent()
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
      $(card).replaceWith(data);
    });
  });
}
