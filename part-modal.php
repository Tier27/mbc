<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">Book Reservation</h4>
      </div>
      <?php if (MAINTENANCE_MODE == true) : ?>
      <div class="modal-body">
	The online booking system is currently down for maintenance. Please call the bowling desk to create a reservation.
      </div>
      <?php else : ?>
      <div class="modal-body">
        <div class="well" id="manage-reservation-target">
          <form class="form-horizontal reservations" method="post" action="<?php echo RESERVATION_SYSTEM_URL; ?>/reservation/create">
            <fieldset>
              <div class="control-group">
                <label class="control-label" for="reservationtime">Reservation Date:</label>
                <div class="controls">
                  <div class="controls">
		    <select name="date" class="input-sm" id="book-reservation-date">
		      <option>There are no availabile times this week.</option>
		    </select>
                  </div>
                </div>
              </div>
              <div class="control-group">

                <div class="inline">
                <label class="control-label" for="book-reservation-bowlers">Bowlers</label>
                <div class="controls">
                  <select type="text" class="input-sm" id="book-reservation-bowlers" name="bowlers" data-original-title="Bowlers">
                    <option value=1>1</option>
                    <option value=2>2</option>
                    <option value=3>3</option>
                    <option value=4>4</option>
                    <option value=5>5</option>
                    <option value=6>6</option>
                    <option value=7 class="two-lanes">7</option>
                    <option value=8 class="two-lanes">8</option>
                    <option value=9 class="two-lanes">9</option>
                    <option value=10 class="two-lanes">10</option>
                    <option value=11 class="two-lanes">11</option>
                    <option value=12 class="two-lanes">12</option>
                  </select>
                </div> 
		</div>
                <div class="inline">
                <label class="control-label">Lanes</label>
                <div class="controls">
                  <select type="text" class="input-sm" id="book-reservation-lanes" name="lanes" data-original-title="Lanes">
                    <option value=1>1</option>
                    <option value=2>2</option>
                  </select>
                </div> 
		</div>
		<div class="inline">
                <label class="control-label">Start</label>
                <div class="controls">
		<?php 
			$date = new rvDate( date('Y-m-d', strtotime(' + 1 day ')) ); 
		?>
		<div class="dropdown">
                  <select type="text" class="input-sm" name="start_hour">
                  </select>
		</div>
                </div> 
                </div>

                <div class="inline">
                <label class="control-label">Hours</label>
                <div class="controls">
                  <select type="text" class="input-sm" name="number_of_hours" id="book-reservation-hours-count" name="lanes" data-original-title="Lanes">
                    <option value=1>1</option>
                    <option value=2>2</option>
                  </select>
                </div> 
		</div>
                <div class="inline">  
                <div class="controls">
                  Price: <span class="inline" id="book-reservation-price-display">$0</span>
                    <input type="text" class="form-control hide" name="price" id="book-reservation-price" value=0>
                </div>  
                </div>
              </div>
		<span class="silly-notice" style="font-size: 11px; color: grey; padding-bottom: 0px; display: block;">If options are greyed out, it is because they are not available at the selected time</span>
              
              <br>

              <!--<form role="form" class="reservations">-->
                <div class="row">
                  <label class="col-xs-4" for="name">First name:*</label>
                  <div class="col-xs-8">
                    <input type="text" class="form-control" name="first-name" id="book-reservation-first-name" placeholder="First name">
                  </div>
                </div>
                <div class="row">
                  <label class="col-xs-4" for="name">Last name:*</label>
                  <div class="col-xs-8">
                    <input type="text" class="form-control" name="last-name" id="book-reservation-last-name" placeholder="Last name">
                  </div>
                </div>
                <div class="row">
                  <label class="col-xs-4" for="company">Company:</label>
                  <div class="col-xs-8">
                    <input type="text" class="form-control" name="company" id="book-reservation-company" placeholder="Company">
                  </div>
                </div>
                <div class="row">
                  <label class="col-xs-4" for="phone">Phone:*</label>
                  <div class="col-xs-8">
                    <input type="text" class="form-control" name="phone" id="book-reservation-phone" placeholder="Phone">
                  </div>
                </div>
                <div class="row">
                  <label class="col-xs-4" for="email">Email:*</label>
                  <div class="col-xs-8">
                    <input type="text" class="form-control" name="email" id="book-reservation-email" placeholder="Email">
                  </div>
                </div>
                <div class="row">
                  <label class="col-xs-4" for="notes">Notes:</label>
                  <div class="col-xs-12">
                    <textarea class="form-control" name="notes" id="book-reservation-notes" placeholder="Notes"></textarea>
                  </div>
                </div>
                <div class="row">
		<br>
                </div>
                <div class="row">
                  <div class="col-xs-8">
                    <input type="text" class="input-sm hide" name="computer_hours" id="book-reservation-hours" data-original-title="Bowlers">
                  </div>
                </div>
                <div class="row">
                  <div class="col-xs-8">
                    <input class="form-control hide" id="book-reservation-action" placeholder="Action" value="add_reservation">
                  </div>
                </div>
	      <div id="book-reservation-information">
Please read before booking:
<br>
- MBC is a 21+ venue everyday, except for 11am-7pm on Saturdays & Sundays.
<br>
- Be sure to let us know if you will require bumpers, as only 2 of our 6 lanes have them and they're booked by families' on a first come, first serve basis.
<br>
<span style="font-weight: bold">Reservation payment is <span style="text-transform: uppercase; background: yellow;">non-refundable</span>. If a guest cancels a reservation up to 48 hours in advance of the reserved date, a credit will be issued for a future booking valid for up to one calendar year. If a guest cancels <span style="text-decoration: underline">within 48 hours of reserved date</span>, no credit will be offered.</span>

	      </div>
              <div id="book-reservation-result" hidden>Result</div>


<?php


?>

            </fieldset>
	    <input type="value" name="ID" style="display: none;">
	    <input type="value" name="web" value=1 style="display: none;">
	    <input type="submit" style="display: none;">
        </div> 

      </div>
      <?php endif; ?>
      <div class="modal-footer">
        <p class="label label-warning pull-left" id="book-reservation-required-message" hidden>Some Fields are Blank</p>
        <button type="button" class="btn btn-default" id="book-reservation-close" data-dismiss="modal" id="close-modal">Close</button>
        <button type="button" class="btn btn-primary" id="book-reservation-submit">Book and pay</button>
	<input type="hidden" class="hide" name="status_id" value="5">
	<input type="hidden" id="RID">
	<div class="hide" id="anf"></div>
      </div>
          </form>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<button type="button" class="btn btn-success hide" id="update-contents" data-id="null">&nbsp;&nbsp;&nbsp;</button>
<button type="button" class="btn btn-default hide" id="update-image-contents" data-id="null">&nbsp;&nbsp;&nbsp;</button>


<script>
var RESERVATION_SYSTEM_URL = "<?php echo RESERVATION_SYSTEM_URL; ?>";
</script>
