<!-- New Item creation -->
<div class="modal fade" id="exitInstance" tabindex="-1" role="dialog" aria-label="exitInstanceModalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exitInstanceModalLabel"><i></i>Exit AQuA2-Cloud</h5>
                </div>
                <div class="modal-body">
					<p><label for="exitInstance">You can leave your instance running if you wish to return to the file library or navigate other portions of the site. Otherwise, you can terminate your instance here. Non-busy instances that have not recieved any user commands for over 30 minutes will auto-terminate.</label></p>
                </div>
                <div class="modal-footer">
					<button type="button" id="exitInstanceModalBtnResume" class="btn btn-warning" onClick="UIR_exitInstanceResume();return false;"><i class="fa fa-arrow-circle-left"></i> Resume </button>
					<button type="button" class="btn btn-warning" onClick="redirectToHome(); return false;"><i class="fa fa-arrow-circle-left"></i> Return to home</button>
                    <button type="button" class="btn btn-danger" id="terminateAQuAInstanceButton" onClick="UActionSimple('TerminateInstance', false);"><i class="fa fa-times-circle"></i> Terminate Instance</button>
                </div>
            </div>
        </div>
    </div>
	<!--///////////////////////////////////////////////////////////////////////////////////////-->
    <div class="modal fade" id="noInstance" tabindex="-1" role="dialog" aria-label="noInstanceModalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="noInstanceModalLabel"><i></i>AQuA2-Cloud Instancing</h5>
                </div>
                <div class="modal-body">
                    <p><label for="noInstance">No active AQuA2-Cloud instances registered to you were found. Would you like to create a new instance?</label></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" onclick="UActionSimple('CreateInstance', true);"><i class="fa fa-plus-circle"></i> Create New Instance</button>
                    <button type="button" class="btn btn-info" onClick="redirectToHome();return false;"><i class="fa fa-arrow-circle-left"></i> Return</button>
                </div>
            </div>
        </div>
    </div>
	<!--///////////////////////////////////////////////////////////////////////////////////////-->
    <div class="modal fade" id="discoverInstance" tabindex="-1" role="dialog" aria-label="discoverInstanceModalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="discoverInstanceModalLabel"><i></i>AQuA Instancing</h5>
                </div>
                <div class="modal-body">
                    <p><label for="discoverInstance">Either a new AQuA instance is being created for you or the registry is being checked for an existing instance. Please be patient. This usually takes approximately 20-30 seconds, but can take as long as 2 minutes...</label></p>
					<div class="d-flex justify-content-center">
					  <div class="spinner-grow" role="status">
						<span class="sr-only">Working...</span>
					  </div>
					</div>
					<div >[Instance information]</div>
					<div id ='discoverInstanceModalInstanceInfo'>Retrieving...</div>
					<div id="checkInstanceRunningCountdown"></div>
						  
						  
				</div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-warning" onClick="redirectToHome();return false;"><i class="fa fa-arrow-circle-left"></i> Return to home</button>
                    <button type="button" class="btn btn-danger" onClick="killInProgress = true; return false;"><i class="fa fa-bolt"></i> Force Terminate</button>
					<p><label for="createAQuAInstance">Note 1: You can return to file manager or home page anytime while waiting for and using your AQuA instance. If you ever get stuck, you can also force terminate all AQuA instances and records registered to you. This is a last resort.</label></p>
					<p><label for="createAQuAInstance">Note 2: Working with high definition imaging data can use lots of bandwidth! This is okay from our end, but if you experience long refresh times, try slightly reducing the resolution at which you recieve the images via the downsampling ratios bar. This does not reduce the resolution of the imaging data held within the back-end, nor does it reduce the resolution at which it is processed.</label></p>
                </div>
            </div>
        </div>
    </div>
	<!--///////////////////////////////////////////////////////////////////////////////////////-->
    <div class="modal fade" id="busyInstance" tabindex="-1" role="dialog" aria-label="busyInstanceModalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="busyInstanceModalLabel"><i></i>Instance is busy...</h5>
                </div>
                <div class="modal-body">
					<div class="d-flex justify-content-center">
					  <div class="spinner-grow" role="status">
					  </div>
					</div>
					<p></p>
					<div style=" width: 100%;">
						<textarea style="width: 100%; max-width: 100%; font-size: 11px;" id="busyModalConsole" rows="8"></textarea>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-warning" onClick="redirectToHome(); return false;"><i class="fa fa-arrow-circle-left"></i> Return to home</button>
						<button type="button" class="btn btn-danger" onClick="UActionSimple('TerminateInstance');"; return false;"><i class="fa fa-bolt"></i> Force Terminate</button>
					</div>
				</div>
            </div>
        </div>
    </div>
	<!--///////////////////////////////////////////////////////////////////////////////////////-->
    <div class="modal fade" id="killInstance" tabindex="-1" role="dialog" aria-label="killInstanceModalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="killInstanceModalLabel"><i></i>Force Termination</h5>
                </div>
                <div class="modal-body">
                    <p><label for="killInstance">Force terminating all AQuA instances registered under your user ID. Please remain on this page. Leaving prior to completion will cancel the operation.</label></p>
					<progress value="0" max="15" id="killProgressBar"></progress>
					<p id="killCountdown"></p>
				</div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-warning" onClick="redirectToHome();return false;"><i class="fa fa-times-circle"></i> Cancel Force Termination</button>
                </div>
            </div>
        </div>
    </div>
	<!--///////////////////////////////////////////////////////////////////////////////////////-->
    <div class="modal fade" id="instanceException" tabindex="-1" role="dialog" aria-label="instanceExceptionModalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="instanceExceptionModalLabel"><i></i>Instance Exception</h5>
                </div>
                <div class="modal-body">
					<p id="exceptionMessage"></p>
                    <p></p>
                    <div style=" width: 100%;">
						<textarea style="width: 100%; max-width: 100%; font-size: 11px;" id="exceptionModalConsole" rows="8"></textarea>
					</div>
                </div>
                <div class="modal-footer">
					<button type="button" id="systemFailedModalBtnResume" class="btn btn-warning" onClick="UIR_clearInstanceException();"><i class="fa fa-arrow-circle-left"></i> Reset </button>
                    <button type="button" class="btn btn-warning" onClick="redirectToHome(); return false;"><i class="fa fa-arrow-circle-left"></i> Return to home </button>
                    <button type="button" class="btn btn-danger" onClick="UActionSimple('TerminateInstance', false);"><i class="fa fa-bolt"></i> Terminate Instance </button>
				</div>
            </div>
        </div>
    </div>