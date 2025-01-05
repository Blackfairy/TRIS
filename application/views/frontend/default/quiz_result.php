<?php
// Assuming $user_full_name and $manuscript_title are already defined and passed to the view
$user_full_name = $this->session->userdata('name'); // Example method to fetch user's name

// Retrieve the manuscript title from the query parameters
$manuscript_title = isset($_GET['title']) ? urldecode($_GET['title']) : 'Unknown Manuscript Title';

// Use $manuscript_title as needed in your PHP code



?>
<div class="row">
    <div class="col-lg-12">
        <div class="card text-white bg-quiz-result-info mb-3">
            <div class="card-body">
                <h5 class="card-title">
                    <?php if ($total_correct_answers == $total_questions): ?>
                        Congratulations! You got a perfect score!
                        <!-- Button to request a certificate -->
                        <button id="requestCertificate" class="btn btn-primary mt-2">
                            Request Certificate
                        </button>
                    <?php else: ?>
                        Review the manuscript materials to expand your learning.
                    <?php endif; ?>
                </h5>
                <p class="card-text">You got <?php echo $total_correct_answers; ?> out of <?php echo $total_questions; ?> correct.</p>
            </div>
        </div>
    </div>
</div>

<?php foreach ($submitted_quiz_info as $each):
    $question_details = $this->crud_model->get_quiz_question_by_id($each['question_id'])->row_array();
    $options = json_decode($question_details['options']);
    $correct_answers = json_decode($each['correct_answers']);
    $submitted_answers = json_decode($each['submitted_answers']);
?>
<div class="row mb-4">
    <div class="col-lg-12">
        <div class="card text-left card-with-no-color-no-border">
            <div class="card-body">
                <h6 class="card-title"><img src="<?php echo $each['submitted_answer_status'] == 1 ? base_url('assets/frontend/default/img/green-tick.png') : base_url('assets/frontend/default/img/red-cross.png'); ?>" alt="" height="15px;"> <?php echo $question_details['title']; ?></h6>
                <?php for ($i = 0; $i < count($correct_answers); $i++): ?>
                    <p class="card-text"> -
                        <?php echo $options[($correct_answers[$i] - 1)]; ?>
                        <img src="<?php echo base_url('assets/frontend/default/img/green-circle-tick.png'); ?>" alt="" height="15px;">
                    </p>
                <?php endfor; ?>
                <p class="card-text"> <strong><?php echo get_phrase("submitted_answers"); ?>: </strong> [
                    <?php
                    $submitted_answers_as_csv = "";
                    for ($i = 0; $i < count($submitted_answers); $i++){
                        $submitted_answers_as_csv .= $options[($submitted_answers[$i] - 1)].', ';
                    }
                    echo rtrim($submitted_answers_as_csv, ', ');
                    ?>
                    ]</p>
            </div>
        </div>
    </div>
</div>
<?php endforeach; ?>
<div class="text-center">
    <a href="javascript::" name="button" class="btn btn-sign-up mt-2" style="color: #fff;" onclick="location.reload();">
        <?php echo get_phrase("take_again"); ?>
    </a>
</div>

<!-- Success Modal -->
<div class="modal fade" id="successModal" tabindex="-1" role="dialog" aria-labelledby="successModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="successModalLabel">Success</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        Your certificate request has been sent.
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" id="successModalButton">OK</button>
      </div>
    </div>
  </div>
</div>

<!-- Error Modal -->
<div class="modal fade" id="errorModal" tabindex="-1" role="dialog" aria-labelledby="errorModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="errorModalLabel">Error</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        There was an error sending the certificate request.
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- JavaScript to handle the certificate request -->
<script type="text/javascript">
    document.getElementById('requestCertificate').addEventListener('click', function() {
        $.ajax({
            url: '<?php echo site_url('home/my_messages/send_new'); ?>',
            method: 'POST',
            data: {
                subject: 'Certificate Request',
                message: 'I, <?php echo $user_full_name; ?>, have achieved a perfect score in the quiz for the manuscript <?php echo "Manuscript Title: " . htmlspecialchars($manuscript_title); ?> on <?php echo date("F j, Y"); ?> and would like to request a certificate.',
                receiver_id: '<?php echo $admin_id; ?>'  // Replace with the admin's user ID
            },
            success: function(response) {
                $('#successModal').modal('show');
            },
            error: function(error) {
                $('#errorModal').modal('show');
            }
        });
    });

    // Redirect on modal close
    document.getElementById('successModalButton').addEventListener('click', function() {
        window.location.href = '<?php echo site_url('home/my_messages'); ?>';
    });
</script>
