<?php

define('TITLE', "User Guide");
include '../assets/layouts/header.php';

?>

</br>
<main role="main" class="container">
<p>AQuA2-Cloud is full-stack application with a web browser interface that allows you to perform activity analysis within biological imaging data after uploading your data to the service.</p>

<p>This guide will provide starting instruction in short 4 sections on how to upload your data, how to create an AQuA2-Cloud instance, how to use the AQuA2-Cloud instance, and how to save and download your processing results.</p>

<p><u><strong>Supported web browsers:</strong></u></p>

<p>- Edge</p>

<p>- Firefox </p>

<p>- Chrome</p>



<p>&nbsp;</p>

<p><u><strong>Section 1: Uploading your data/configuration files</strong></u></p>

<p>Upon account creation and approval, you can use the in-browser file management page to upload smaller files (only recommended up to 25 MB) such as preference/configuration files, toy imaging movies, and other data at your discretion.</p>

<p><img alt="" src="images/FM_upload.PNG" style="height:135px; width:800px" /></p>

<p>For uploading your imaging movies and larger files, you should use file transfer protocol (FTP) and an FTP client. An FTP account with the same username and password you use for this website is created in parallel once your website account is verified.</p>

<p>For an FTP client on Windows, we recommend WinSCP.</a></p>

<p>&nbsp;</p>

<p><u><strong>Section 2: Creating an AQuA2-Cloud instance</strong></u></p>

<p>To start an AQuA2-Cloud instance, select the application entry in the dropdown panel to proceed to the application&#39;s interface page.</p>

<p><img alt="" src="images/App_dropdown.PNG" style="height:224; width:212px" /></p>

<p>On the application&#39;s interface page, having no instance currently started/running will present you with the create instance dialog.</p>

<p><img alt="" src="images/App_create.PNG" style="height:293px; width:518px" /></p>

<p>Select &quot;Create New Instance&quot; to create an AQuA2-Cloud instance. The server will now create a logical instance and a connection established.</p>

<p><img alt="" src="images/App_start.PNG" style="height:395px; width:516px" /></p>

<p>After the instance is started, you can quickly check your connection status by the instance status indicator. Orange means updating or busy, green means connected.</p>

<p><img alt="" src="images/App_ILS.PNG" style="height:278px; width:329px" /></p>

<p>Connection stability influences the appearance of any connection-related errors while you use the application. Selecting reset and refreshing the page almost always fixes any such issues. If you run into a case where you cannot continue, always feel free to submit a bug report.</p>

<p>Since this is an application that fundamentally includes the viewing of imaging movie frames as part of the analysis process, we've included capability for downsampling the displayed image frames within your browser. You can enter values between 0.1 and 1 in the downsampling ratio fields. Reducing the displayed resolutions will reduce the data size required to be transmitted to your browser, and may help on lower bandwidth connections. Data processing is always done at full resolution on the server. </p>

<p>&nbsp;</p>

<p><u><strong>Section 3: Using an AQuA2-Cloud instance</strong></u></p>

<p>After creating a new instance, you have to create or load an AQuA2 project. To create a new project, use the integrated file browser to navigate to your source imaging movie and enable the checkbox next to it. All accounts are given a folder and sample data titled &quot;sampleData.tif&quot; upon account registration. We will use this sample data as a demo.</p>

<p>Note that panels with red backgrounds are not currently interactable, while panels with grey backgrounds can be interacted with.</p>

<p><img alt="" src="images/App_createProj.PNG" style="height:562px; width:273px" /></p>

<p>After selecting &quot;Create New Project&quot;, the interactability of some panels will change, the first frame of the imaging movie will be displayed, and the detection pipeline panel will become available.</p>

<p>The most important panel of Activity Quantification and Analysis' (AQuA2) interface is the detection pipeline. This 6 step pipeline consists of all stages related to the detection of intensity-based activity within the imaging movie. Various parameters are modifiable throughout the pipeline and hovering your mouse cursor over each field will usually give a context tooltip as to what the modifiers algorithmically do.</p>

<p>You may either run each step individually by clicking the &quot;Run&quot; button near the bottom of the detection panel, or run all steps at once using default or loaded (by loading predefined detection settings from a .csv via the &quot;Load&quot; button) detection settings by clicking the &quot;Run all steps&quot; button.</p>

<p>After reaching and running the final step ([6] Feature) and pressing the extract button, the proofreading table, favorite table, and export panel will enable.</p>

<p><img alt="" src="images/App_Callout.PNG" style="height:500px; width:1017px" /></p>

<p>Adjust the values within the proofreading table if needed to define the attribute ranges for events to be added prior to clicking the &quot;Add all filtered&quot; button. This button will then add all detected events that adhere to the filters specified within the proofreading table to the favorites table. A simpler and directly usable way to add events of interest to the favorites table, and thus aquire detailed information about those events, including their curves, is to select the &quot;View/Favorite&quot; button, to enter cursor click mode and select the colored event's pixels within the viewport that you are interested in. The application will then add that specific event to the favourites table. "Show curves" button will display the curve of all selected events, and will overlay the curves if multiple events are selected.</p>

<p>When you have multiple events added to your favorites table, you can select an event within the favorites table by clicking on that events row (not the first column), and the application will automatically move the current frame to the frame corresponding to that event&#39;s peak and display the event&#39;s curve within the curve plot.</p>

<p><u><strong>Section 4: Save your experiment and download your results</strong></u></p>

<p>After having run all steps of the detection pipeline as well as adding any events of interest, you may save your experiment and the processing results via the export panel. <strong>AQuA Project</strong> will save a .mat file that can be loaded in either the cloud version of AQuA2 or the AQuA2 standalone MATLAB version (all files are cross compatible). <strong>Feature Table</strong> will export a .csv containing all details regarding all detected/favorited events. <strong>Movie with overlay</strong> will export a .tif which consists of the original imaging movie but with event activity and their associated coloring baked in. The "Save waves" button will generate a .csv file for each event that is currently checked in the favorites table.</p>

<p><img alt="" src="images/App_Export.PNG" style="height:179px; width:327px" /></p>

<p>&nbsp;</p>

<p>We are always happy to answer any questions. Do not hesitate to contact us with feedback, questions, or bug reports.</p>


<?php

include '../assets/layouts/footer.php';

?>