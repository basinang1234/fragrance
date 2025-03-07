<script>
function handleVote(contentType, contentId, value) {
    $.post('vote.php', {
        content_type: contentType,
        content_id: contentId,
        value: value
    })
    .done(function(data) {
        if (data.success) {
            $(`#likes-${contentId}`).text(data.likes);
            $(`#dislikes-${contentId}`).text(data.dislikes);
            // Update button states
            $(`.vote-btn[onclick*="handleVote('${contentType}', ${contentId}, 1)`).toggleClass('active', data.user_vote === 1);
            $(`.vote-btn[onclick*="handleVote('${contentType}', ${contentId}, -1)`).toggleClass('active', data.user_vote === -1);
        } else {
            alert(data.message || 'An error occurred');
        }
    })
    .fail(function(jqXHR) {
        alert(`Request failed: ${jqXHR.statusText}`);
    });
}

// Comment form submission
$('#commentForm').submit(function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    
    $.ajax({
        url: 'comment.php',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(data) {
            if (data.success) {
                location.reload(); // Reload to show new comment
            } else {
                alert(data.message || 'Failed to add comment');
            }
        },
        error: function(jqXHR) {
            alert(`Request failed: ${jqXHR.statusText}`);
        }
    });
});
</script>