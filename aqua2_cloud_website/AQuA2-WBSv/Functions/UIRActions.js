function UIR_NewProjTabCh1FileDirRefer()
{
    if (!document.getElementById("NewProjTabCh1FileDirRefer").disabled)
    {
        if (!waitingOnServer)
        {
            if ((referencedFileFull == "") && (!(document.getElementById("NewProjTabCh1FileDirInput").value)))
            {
                toast("No source data is currently referenced! Use the checkboxes in the integrated file browser.");
            }
            else
            {
                if (document.getElementById("NewProjTabCh1FileDirInput").value != referencedFileFull)
                {
                    if ((referencedFileFull == "") && (document.getElementById("NewProjTabCh1FileDirInput").value))
                    {
                        if (document.getElementById("NewProjTabCh2FileDirInput").value)
                        {
                            toast("You cannot clear channel 1's reference while having a file referenced for channel 2...");
                        }
                        else
                        {
                            document.getElementById("NewProjTabCh1FileDirInput").value = "";
                            toast("Channel 1 file reference cleared...");
                            BackEndUISync(0, project_state);
                        }
                    }
                    else
                    {
                        if (document.getElementById("NewProjTabCh2FileDirInput").value == referencedFileFull)
                        {
                            toast("Notice: The data referenced for channel 1 is indentical to that referenced for channel 2. Be sure you meant to do this...");
                            document.getElementById("NewProjTabCh1FileDirInput").value = referencedFileFull;
                        }
                        else
                        {
                            toast("Source data referenced");
                            document.getElementById("NewProjTabCh1FileDirInput").value = referencedFileFull;
                        }
                        BackEndUISync(0, project_state);
                    }
                }
                else
                {
                    toast("You've already referenced this source data!");
                }
            }
        }
    } else {toast("This function is currently disabled...");}
}

function UIR_NewProjTabCh2FileDirRefer()
{
    if (!document.getElementById("NewProjTabCh2FileDirRefer").disabled) //Process only if this button is enabled
    {
        if (!waitingOnServer)
        {
            if ((referencedFileFull == "") && (!(document.getElementById("NewProjTabCh2FileDirInput").value))) //referencedFileFull is populated when a checkbox is toggled, its set to "" otherwise
            {
                toast("No source data is currently referenced! Use the checkboxes in the integrated file browser.");
            }
            else
            {
                if ((!document.getElementById("NewProjTabCh1FileDirInput").value) && (!(referencedFileFull == "")))
                {
                    toast("You cannot reference data for channel 2 without having a channel 1...");
                }
                else
                {
                    if (document.getElementById("NewProjTabCh2FileDirInput").value != referencedFileFull)
                    {
                        if  (referencedFileFull == document.getElementById("NewProjTabCh1FileDirInput").value)
                        {
                            toast("Notice: The data referenced for channel 2 is indentical to that referenced for channel 1. Be sure you meant to do this...");
                            document.getElementById("NewProjTabCh2FileDirInput").value = referencedFileFull;
                        }
                        else
                        {
                            if (!referencedFileFull)
                            {
                                document.getElementById("NewProjTabCh2FileDirInput").value = "";
                                toast("Channel 2 file reference cleared...");
                            }
                            else
                            {
                                document.getElementById("NewProjTabCh2FileDirInput").value = referencedFileFull;
                                toast("Source data referenced");
                            }
                        }
                        BackEndUISync(0, project_state);
                    }
                    else
                    {
                        toast("You've already referenced this source data!");
                    }
                }
            }
        }
    }
    else
    {
        toast("This function is currently disabled...");
    }
}

$("#NewProjTabDataTypeDropDown").on('change paste', function()
{
    if (!waitingOnServer)
    {
        BackEndUISync(1, project_state);
    }
});

function numericSettingBoxEntryActionTypeA(elementIDString)
{
    if (custom_isNumeric.test(document.getElementById(elementIDString).value))
    {
        if (parseFloat(document.getElementById(elementIDString).value) < 0)
        {
            toast("There are no parameter inputs in this application for which negative values make sense!");
            $(".overlay").show();
            UActionPayload("FEUIRfsh",[]);
        }
        else
        {
            BackEndUISync(0, project_state);
        }
    }
    else
    {
        //Bad input, reject and reload from back-end
        toast("Only numeric entries are accepted");
        $(".overlay").show();
        UActionPayload("FEUIRfsh",[]);
    };
}

$("#NewProjTabTempResInput").on('change paste', function()
{
    if (!waitingOnServer)
    {
        numericSettingBoxEntryActionTypeA("NewProjTabTempResInput");
    }
});

$("#NewProjTabSpatialResInputInput").on('change paste', function()
{
    if (!waitingOnServer)
    {
        numericSettingBoxEntryActionTypeA("NewProjTabSpatialResInputInput");
    }
});

$("#NewProjTabExcludePixelsInput").on('change paste', function()
{
    if (!waitingOnServer)
    {
        numericSettingBoxEntryActionTypeA("NewProjTabExcludePixelsInput");
    }
});

function UIR_exitInstanceResume()
{
    hideAllModals();
}

function NewProjTabButtonCreate()
{
    if (!document.getElementById("NewProjTabButtonCreate").disabled)
    {
        //console.log("NewProjTabButtonCreate called...");
        //console.log("waitingOnServer: " + waitingOnServer);
        if (!waitingOnServer)
        {
            if (document.getElementById("NewProjTabCh1FileDirInput").value == "")
            {
                toast("CH1 has no referenced source data. Cannot create project...");
            }
            else
            {
                UActionPayloadSC("cmdNewPj",[],"NewProjTabButtonCreate");
                toast("Attempting to create project...");
            }
        }
        else
        {
            toast("You cannot issue compute/IO commands too quickly (2.5s grace)...");
        }
    }
    else
    {
        toast("This function is currently disabled...");
    }
}

function UIR_clearInstanceException()
{
    UActionSimple("ClearInstanceException",false);
}

function isMAT(filename) 
{
    var ext = getExtension(filename);
    switch (ext.toLowerCase()) {
    case 'mat':
    //etc
    return true;
}
    return false;
}

function UIR_LoadProjTabFileDirRefer()
{
    if (!document.getElementById("LoadProjTabFileDirRefer").disabled)
    {
        if (!waitingOnServer)
        {
            if (referencedFileFull != "")
            {
                if (isMAT(referencedFileFull))
                {
                    if (document.getElementById("LoadProjTabCh1FileDirInput").value != referencedFileFull)
                    {
                        document.getElementById("LoadProjTabCh1FileDirInput").value = referencedFileFull;
                        toast("Source data referenced");
                    }
                    else
                    {
                        toast("You've already referenced this experiment!");
                    }
                }
                else
                {
                    toast("You must reference a valid .mat file...");
                }
            }
            else
            {
                if (document.getElementById("LoadProjTabCh1FileDirInput").value != "")
                {
                    document.getElementById("LoadProjTabCh1FileDirInput").value = "";
                    toast("Load project file reference cleared...");
                }
                else
                {
                    toast("No source data is currently referenced! Use the checkboxes in the integrated file browser.");
                }
            }
        }
    }
    else
    {
        toast("This function is currently disabled...");
    }
}

function UIR_LoadProjTabButtonCreate()
{
    if (!document.getElementById("LoadProjTabButtonCreate").disabled)
    {
        if (!waitingOnServer)
        {
            if (document.getElementById("LoadProjTabCh1FileDirInput").value != "")
            {
                toast("Attempting to load experiment...");
                UActionPayloadSC("loadExpr",["location",10000000,document.getElementById("LoadProjTabCh1FileDirInput").value],"UIR_LoadProjTabButtonCreate");
            }
            else
            {
                toast("No source data is currently referenced! Use the checkboxes in the integrated file browser.");
            }
        }
    }
    else
    {
        toast("This function is currently disabled...");
    }
}

function UIR_DetectPipelineTabClick(e)
{
    //console.log("DetectPipelineTabClick called...");
    if (!waitingOnServer)
    {
        if (e <= DetectPipelineMaximumTab)
        {
            DetectPipelineCurrentTab = e;
            BackEndUISync(0, project_state);
            return;
        }
    }
    //Block the tab switch and reset the tab back to the BE active tab
    $('.u-tabs-2').tabs({active: DetectPipelineCurrentTab}); //This draws the correct tab.
    switch(DetectPipelineCurrentTab)
    {
        case 1:
            document.getElementById("DetectPipePreProcessTab").classList.add('active');
            document.getElementById("DetectPipeActiveTab").classList.remove('active');
            document.getElementById("DetectPipeTemporalTab").classList.remove('active');
            document.getElementById("DetectPipeSpatialTab").classList.remove('active');
            document.getElementById("DetectPipeGlobalTab").classList.remove('active');
            document.getElementById("DetectPipeFeatureTab").classList.remove('active');
            break;
        case 2:
            document.getElementById("DetectPipePreProcessTab").classList.remove('active');
            document.getElementById("DetectPipeActiveTab").classList.add('active');
            document.getElementById("DetectPipeTemporalTab").classList.remove('active');
            document.getElementById("DetectPipeSpatialTab").classList.remove('active');
            document.getElementById("DetectPipeGlobalTab").classList.remove('active');
            document.getElementById("DetectPipeFeatureTab").classList.remove('active');
            break;
        case 3:
            document.getElementById("DetectPipePreProcessTab").classList.remove('active');
            document.getElementById("DetectPipeActiveTab").classList.remove('active');
            document.getElementById("DetectPipeTemporalTab").classList.add('active');
            document.getElementById("DetectPipeSpatialTab").classList.remove('active');
            document.getElementById("DetectPipeGlobalTab").classList.remove('active');
            document.getElementById("DetectPipeFeatureTab").classList.remove('active');
            break;
        case 4:
            document.getElementById("DetectPipePreProcessTab").classList.remove('active');
            document.getElementById("DetectPipeActiveTab").classList.remove('active');
            document.getElementById("DetectPipeTemporalTab").classList.remove('active');
            document.getElementById("DetectPipeSpatialTab").classList.add('active');
            document.getElementById("DetectPipeGlobalTab").classList.remove('active');
            document.getElementById("DetectPipeFeatureTab").classList.remove('active');
            break;
        case 5:
            document.getElementById("DetectPipePreProcessTab").classList.remove('active');
            document.getElementById("DetectPipeActiveTab").classList.remove('active');
            document.getElementById("DetectPipeTemporalTab").classList.remove('active');
            document.getElementById("DetectPipeSpatialTab").classList.remove('active');
            document.getElementById("DetectPipeGlobalTab").classList.add('active');
            document.getElementById("DetectPipeFeatureTab").classList.remove('active');
            break;
        case 6:
            document.getElementById("DetectPipePreProcessTab").classList.remove('active');
            document.getElementById("DetectPipeActiveTab").classList.remove('active');
            document.getElementById("DetectPipeTemporalTab").classList.remove('active');
            document.getElementById("DetectPipeSpatialTab").classList.remove('active');
            document.getElementById("DetectPipeGlobalTab").classList.remove('active');
            document.getElementById("DetectPipeFeatureTab").classList.add('active');
            break;
    }
}

$("#DetectPipeTab1RegistrateDropdown").on('change paste', function()
{
    if (!waitingOnServer)
    {
        BackEndUISync(0, project_state);
    }
});

$("#DetectPipeTab1PhotobleachDropdown").on('change paste', function()
{
    if (!waitingOnServer)
    {
        BackEndUISync(0, project_state);
    }
});

function UIR_NewProjTabButtonLoadPreset()
{
    if (!document.getElementById("NewProjTabButtonLoadPreset").disabled)
    {
        if (!waitingOnServer)
        {
            if (referencedFileFull)
            {
                if (isCSV(referencedFileFull))
                {
                    if (confirm("The instance will attempt to load the currently referenced presets .csv. Note: Only 3 presets may be defined per csv due system limitations. If you need more than 3 presets, you can split them across multiple csv's."))
                    {
                        toast("Attempting to load preset...");
                        UActionPayloadSC("loadPrst",["location",10000000,referencedFileFull],"UIR_NewProjTabButtonLoadPreset");
                    }
                }
                else
                {
                    toast("Referenced file is not of .csv file type...");
                }
            }
            else
            {
                toast("You have not referenced any .csv files...");
            }
        }
    }
    else
    {
        toast("This function is currently disabled...");
    }
}

$("#DetectPipeTab1MedianFilterRadius").on('change paste', function()
{
    if (!waitingOnServer)
    {
        numericSettingBoxEntryActionTypeA("DetectPipeTab1MedianFilterRadius");
    }
});

$("#DetectPipeTab1GaussianFilterRadius").on('change paste', function()
{
    if (!waitingOnServer)
    {
        numericSettingBoxEntryActionTypeA("DetectPipeTab1GaussianFilterRadius");
    }
});

function UIR_DetectPipeRunButton()
{
    if (!document.getElementById("DetectPipeRunButton").disabled)
    {
        if (!waitingOnServer)
        {
            toast("Attempting to run detection pipeline current step...");
            UActionPayloadSC("cmdDPRnB",[],"UIR_DetectPipeRunButton");
        }
    } else{toast("This function is currently disabled...");}
}

function UIR_DetectPipeNextButton()
{
    //console.log("DetectPipeNextButton called...");
    if (!document.getElementById("DetectPipeNextButton").disabled)
    {
        if (!waitingOnServer)
        {
            //console.log("Attempting to run detection pipeline next step...");
            if ($('.u-tabs-2').tabs('option','active') == 0) //If the current tab is preprocess, notify user that once they click run for this tab, the preprocess settings are locked in
            {
                if (confirm("Notice: Pre-processing settings become unchangable once 'Run' or 'Run all steps' command is directed."))
                {
                    UActionPayloadSC("cmdDPNxB",[],"UIR_DetectPipeNextButton");
                }
                return;
            }
            UActionPayloadSC("cmdDPNxB",[],"UIR_DetectPipeNextButton");
        }
    } else{toast("This function is currently disabled...");}
}

//Verification that entry is a valid number - special case (B) for tab 2 thres input
function numericSettingBoxEntryActionTypeB(elementIDString)
{
    if (custom_isNumeric.test(document.getElementById(elementIDString).value))
    {
        if (parseFloat(document.getElementById(elementIDString).value) < 0)
        {
            toast("There are no parameter inputs in this application for which negative values make sense!");
            $(".overlay").show();
            UActionPayload("FEUIRfsh",[]);
        }
        else
        {
            //Input is fine, send to back-end
            var syncValue = document.getElementById(elementIDString).value;
            document.getElementById("DetectPipeTab2IntensityThresSlider").value = syncValue;
            document.getElementById("DetectPipeTab2IntensityThresInput").value = syncValue;
            BackEndUISync(0, project_state);
        }
    }
    else
    {
        //Bad input, reject and reload from back-end
        toast("Only numeric entries are accepted");
        $(".overlay").show();
        UActionPayload("FEUIRfsh",[]);
    };
}

$("#DetectPipeTab2IntensityThresInput").on('change paste', function()
{
    if (!waitingOnServer)
    {
        numericSettingBoxEntryActionTypeB("DetectPipeTab2IntensityThresInput");
    }
});

$("#DetectPipeTab2IntensityThresSlider").on('change paste', function()
{
    if (!waitingOnServer)
    {
        numericSettingBoxEntryActionTypeB("DetectPipeTab2IntensityThresSlider");
    }
});

$("#DetectPipeTab2MinDurInput").on('change paste', function()
{
    if (!waitingOnServer)
    {
        numericSettingBoxEntryActionTypeA("DetectPipeTab2MinDurInput");
    }
});

$("#DetectPipeTab2MinSizeInput").on('change paste', function()
{
    if (!waitingOnServer)
    {
        numericSettingBoxEntryActionTypeA("DetectPipeTab2MinSizeInput");
    }
;
});

$("#DetectPipeTab2MaxSizeInput").on('change paste', function()
{
    if (!waitingOnServer)
    {
        numericSettingBoxEntryActionTypeA("DetectPipeTab2MaxSizeInput");
    }
});

$("#DetectPipeTab2CircularityThresholdInput").on('change paste', function()
{
    if (!waitingOnServer)
    {
        numericSettingBoxEntryActionTypeA("DetectPipeTab2CircularityThresholdInput");
    }
});

$("#DetectPipeTab3EnableTempSegToggle").on('change paste', function()
{
    if (!waitingOnServer)
    {
        BackEndUISync(0, project_state);
    }
});

$("#DetectPipeTab3SeedSizeRelativeInput").on('change paste', function()
{
    if (!waitingOnServer)
    {
        numericSettingBoxEntryActionTypeA("DetectPipeTab3SeedSizeRelativeInput");
    }
});

$("#DetectPipeTab3ZscoreSigSeedInput").on('change paste', function()
{
    if (!waitingOnServer)
    {
        numericSettingBoxEntryActionTypeA("DetectPipeTab3ZscoreSigSeedInput");
    }
});

$("#DetectPipeTab3RiseTimeDiffThresInput").on('change paste', function()
{
    if (!waitingOnServer)
    {
        numericSettingBoxEntryActionTypeA("DetectPipeTab3RiseTimeDiffThresInput");
    }
});

$("#DetectPipeTab3RefineTempAdjacentPeaks").on('change paste', function()
{
    if (!waitingOnServer)
    {
        BackEndUISync(0, project_state);
    }
});

$("#DetectPipeTab3GrowActiveRegionPerPatterns").on('change paste', function()
{
    if (!waitingOnServer)
    {
        BackEndUISync(0, project_state);
    }
});

$("#DetectPipeTab4EnableSpatialSegToggle").on('change paste', function()
{
    if (!waitingOnServer)
    {
        BackEndUISync(0, project_state);
    }
});

		
$("#DetectPipeTab4SourceSizeRatio").on('change paste', function()
{
    if (!waitingOnServer)
    {
        numericSettingBoxEntryActionTypeA("DetectPipeTab4SourceSizeRatio");
    }
});

$("#DetectPipeTab4SourceDetectSensitivity").on('change paste', function()
{
    if (!waitingOnServer)
    {
        numericSettingBoxEntryActionTypeA("DetectPipeTab4SourceDetectSensitivity");
    }
});

$("#DetectPipeTab4TemporalExtensionToggle").on('change paste', function()
{
    if (!waitingOnServer)
    {
        BackEndUISync(0, project_state);
    }
});

$("#DetectPipeTab5DetectGlobalSignalToggle").on('change paste', function()
{
    if (!waitingOnServer)
    {
        BackEndUISync(0, project_state);
    }
});

$("#DetectPipeTab6IgnoreDelayTauToggle").on('change paste', function()
{
    if (!waitingOnServer)
    {
        BackEndUISync(0, project_state);
    }
});

$("#DetectPipeTab6PropagationMetricRelativeToggle").on('change paste', function()
{
    if (!waitingOnServer)
    {
        BackEndUISync(0, project_state);
    }
});

$("#DetectPipeTab6NetworkFeaturesToggle").on('change paste', function()
{
    if (!waitingOnServer)
    {
        BackEndUISync(0, project_state);
    }
});

function UIR_DetectPipeBackButton()
{
    if (!document.getElementById("DetectPipeBackButton").disabled) //Process only if this button is enabled
    {
        if (!waitingOnServer)
        {
            UActionPayloadSC("cmdDPBkB",[],"UIR_DetectPipeBackButton");
            toast("Attempting to return to previous detection pipeline step...");
        }
    } else{toast("This function is currently disabled...");}
}

function UIR_DetectPipeSaveButton()
{
    if (!document.getElementById("DetectPipeSaveButton").disabled)
    {
        if (!waitingOnServer)
        {
            let text;
            var characters = /^[0-9a-zA-Z_-]+$/;
            let user_defined_suffix = prompt("AQuA2-Cloud will save the pipeline settings to a .csv file to the directory that is currently navigated to within this page. Enter the desired suffix for output files names to acknowledge or cancel to return. You may have to refresh the page to see the newly saved files.", "_AQuA2_CloudPiplineSettings_".concat(makeid(7)));
            if (!(user_defined_suffix == null || user_defined_suffix == ""))
            {
                if (characters.test(user_defined_suffix))
                {
                    UActionGetUserPath("getCurrentUserPath", user_defined_suffix, "UIR_DetectPipeSaveButton");
                    toast("Attempting to save pipeline settings to .csv file...");
                }
                else
                {
                    alert('Illegal filename: Please input alphanumeric characters only. No spaces or special characters.');
                }
            }
        }
    } else{toast("This function is currently disabled...");}
    
}

function UIR_DetectPipeLoadButton()
{
    if (!document.getElementById("DetectPipeLoadButton").disabled)
    {
        if (!waitingOnServer)
        {
            if (referencedFileFull)
            {
                if (isCSV(referencedFileFull))
                {
                    if (confirm("The instance will attempt to load the currently referenced options .csv."))
                    {
                        toast("Attempting to load options csv...");
                        UActionPayloadSC("loadOpts",["location",10000000,referencedFileFull],"UIR_NewProjTabButtonLoadPreset");
                    }
                }
                else
                {
                    toast("Referenced file is not of .csv file type...");
                }
            }
            else
            {
                toast("You have not referenced any .csv files...");
            }
        }
    }
    else
    {
        toast("This function is currently disabled...");
    }
}

function UIR_DetectPipeRunAllStepsButton()
{
    if (!document.getElementById("DetectPipeRunAllStepsButton").disabled) //Process only if this button is enabled
    {
        if (!waitingOnServer)
        {
            UActionPayloadSC("RnAlStps",[],"UIR_DetectPipeRunAllStepsButton");
            toast("Attempting to run all detection pipeline steps...");
        }
    } else{toast("This function is currently disabled...");}
}

$("#LayersMinSlider").on('change', function()
{
    if (!(document.getElementById("LayersMinSlider").disabled))
    {
        if (!waitingOnServer)
        {
            document.getElementById("LayersMinSlider").value = String((Math.round(((parseFloat(document.getElementById("LayersMinSlider").value)) + Number.EPSILON) * 100))/ 100);
            BackEndUISync(0, project_state);
        }
    } else {toast("This function is currently disabled...");}
});

$("#LayersMaxSlider").on('change', function()
{
    if (!(document.getElementById("LayersMaxSlider").disabled))
    {
        if (!waitingOnServer)
        {
            document.getElementById("LayersMaxSlider").value = String((Math.round(((parseFloat(document.getElementById("LayersMaxSlider").value)) + Number.EPSILON) * 100))/ 100);
            BackEndUISync(0, project_state);
        }
    } else {toast("This function is currently disabled...");}
});

$("#LayersBrightnessSlider").on('change', function()
{
    if (!(document.getElementById("LayersBrightnessSlider").disabled))
    {
        if (!waitingOnServer)
        {
            document.getElementById("LayersBrightnessSlider").value = String((Math.round(((parseFloat(document.getElementById("LayersBrightnessSlider").value)) + Number.EPSILON) * 100))/ 100);
            BackEndUISync(0, project_state);
        }
    } else {toast("This function is currently disabled...");}
});

$("#LayersBrightness2Slider").on('change', function()
{
    if (!(document.getElementById("LayersBrightness2Slider").disabled))
    {
        if (!waitingOnServer)
        {
            document.getElementById("LayersBrightness2Slider").value = String((Math.round(((parseFloat(document.getElementById("LayersBrightness2Slider").value)) + Number.EPSILON) * 100))/ 100);
            BackEndUISync(0, project_state);
        }
    } else {toast("This function is currently disabled...");}
});

$("#LayersColorBrightnessSlider").on('change', function()
{
    if (!(document.getElementById("LayersColorBrightnessSlider").disabled))
    {
        if (!waitingOnServer)
        {
            document.getElementById("LayersColorBrightnessSlider").value = String((Math.round(((parseFloat(document.getElementById("LayersColorBrightnessSlider").value)) + Number.EPSILON) * 100))/ 100);
            BackEndUISync(0, project_state);
        }
    } else {toast("This function is currently disabled...");}
});

$("#leftViewPortChannel").on('change', function()
{
    if (!(document.getElementById("leftViewPortChannel").disabled))
    {
        if (!waitingOnServer)
        {
            BackEndUISync(0, project_state);
        }
    } else {toast("This function is currently disabled...");}
});

$("#rightViewPortChannel").on('change', function()
{
    if (!(document.getElementById("rightViewPortChannel").disabled))
    {
        if (!waitingOnServer)
        {
            BackEndUISync(0, project_state);
        }
    } else {toast("This function is currently disabled...");}
});

$("#dropDownFeatureOverlayType").on('change', function()
{
    if (!(document.getElementById("dropDownFeatureOverlayType").disabled))
    {
        if (!waitingOnServer)
        {
            BackEndUISync(0, project_state);
        }
    } else {toast("This function is currently disabled...");}
});

$("#dropDownFeatureOverlayFeature").on('change', function()
{
    if (!(document.getElementById("dropDownFeatureOverlayFeature").disabled))
    {
        if (!waitingOnServer)
        {
            BackEndUISync(0, project_state);
        }
    } else {toast("This function is currently disabled...");}
});

$("#dropDownFeatureOverlayColor").on('change', function()
{
    if (!(document.getElementById("dropDownFeatureOverlayColor").disabled))
    {
        if (!waitingOnServer)
        {
            BackEndUISync(0, project_state);
        }
    } else {toast("This function is currently disabled...");}
});

function UIR_LayersUpdateOverlayButton()
{
    if (!document.getElementById("UIR_updateOverlayButton").disabled) //Process only if this button is enabled
    {
        if (!waitingOnServer)
        {
            UActionPayloadSC("cmdLUpOv",[],"UIR_updateOverlayButton");
            toast("Attempting to update overlay...");
        }
        else
        {
            toast("You cannot issue compute/IO commands too quickly (2.5s grace)...");
        }
    } else{toast("This button/function is currently disabled...");}
}

function UIR_MoviePanelGaussFilterButton()
{
    if (!document.getElementById("MoviePanelGaussFilterButton").disabled) //Process only if this button is enabled
    {
        if (!waitingOnServer)
        {
            UActionPayloadSC("cmdGFlBt",[],"UIR_MoviePanelGaussFilterButton");
            toast("Attempting to apply Gaussian filter...");
        }
    } else{toast("This function is currently disabled...");}
}

$("#movLTypeDropdown").on('change', function()
{
    if (!(document.getElementById("movLTypeDropdown").disabled))
    {
        if (!waitingOnServer)
        {
            BackEndUISync(0, project_state);
        }
    } else {toast("This function is currently disabled...");}
});

$("#movRTypeDropdown").on('change', function()
{
    if (!(document.getElementById("movRTypeDropdown").disabled))
    {
        if (!waitingOnServer)
        {
            BackEndUISync(0, project_state);
        }
    } else {toast("This function is currently disabled...");}
});

function UIR_ViewportModeTabClick(e)
{
    if (!waitingOnServer)
    {
        viewportCurrentTab = e;
        BackEndUISync(0, project_state);
    }
    else
    {
        var tmp = viewportCurrentTab - 1;
        $('.u-tabs-3').tabs({active: tmp}); //This draws the correct tab.
        switch(viewportCurrentTab)
        {
            case 1:
                document.getElementById("SingleViewPortTab").classList.add('active');
                document.getElementById("DualViewPortTab").classList.remove('active');
                break;
            case 2:
                document.getElementById("SingleViewPortTab").classList.remove('active');
                document.getElementById("DualViewPortTab").classList.add('active');
                break;
        }
    }
}

function UIR_DRLCellAdd()
{
    if (!document.getElementById("DRLCellAdd").disabled) //Process only if this button is enabled
    {
        if (!waitingOnServer)
        {
            if (confirm("You are about to enter polygon draw mode. You will be limited to the viewport (in single mode) or the left viewport (in dual mode). Left click to define polygon points, right click to finish and close the polygon."))
            {
                cursorCurrentMode = "celldraw";
                polygonDrawMode();
            }
        }
    } else{toast("This function is currently disabled...");}
}

function polygonDrawMode()
{
    cursorClickPolygonDrawModeDisableAllElseHelper();
    document.getElementById("MoviePanelCurrentFrameIndicator").innerHTML = "Image cursor mode";
    //Handle background colors
    //#ffc7c7   -- Disabled red
    //#f0f0f0   -- Normal grey
    document.getElementById("projectTabsContainer").style.backgroundColor = "#ffc7c7";
    document.getElementById("projectTabsButtonContainer").style.backgroundColor = "#ffc7c7";
    document.getElementById("DRLUIContainer").style.backgroundColor = "#ffc7c7";
    document.getElementById("DRLUIContainer02").style.backgroundColor = "#ffc7c7";
    document.getElementById("DRLUIContainer03").style.backgroundColor = "#ffc7c7";
    document.getElementById("DRLUIContainer04").style.backgroundColor = "#ffc7c7";
    document.getElementById("DetectionContainer01").style.backgroundColor = "#ffc7c7";
    document.getElementById("tab-be41").style.backgroundColor = "#ffc7c7";
    document.getElementById("tab-821b").style.backgroundColor = "#ffc7c7";
    document.getElementById("tab-d59d").style.backgroundColor = "#ffc7c7";
    document.getElementById("tab-a49b").style.backgroundColor = "#ffc7c7";
    document.getElementById("tab-7b6c").style.backgroundColor = "#ffc7c7";
    document.getElementById("tab-669a").style.backgroundColor = "#ffc7c7";
    document.getElementById("LayeringContainer01").style.backgroundColor = "#ffc7c7";
    
    //Set a thick green border for movie box
    document.getElementById("movieBoxSFPatch01").style.backgroundColor = "#ffe891";
    document.getElementById("movieBoxSFPatch01").style.borderColor = "#02fc00";
    document.getElementById("movieBoxSFPatch01").style.borderWidth = "3px";
    
    document.getElementById("ProofReadingContainer").style.backgroundColor = "#ffc7c7";
    document.getElementById("FavouriteContainer").style.backgroundColor = "#ffc7c7";
    document.getElementById("ExportContainer").style.backgroundColor = "#ffc7c7";
    
    canvasDrawMode = true;
    cd_complete = false;
    cd_ctx = cd_canvas.getContext("2d");
    document.getElementById("MoviePanelImagePrimary").style.cursor = "crosshair";
}

function resetPolygonVariables()
{
    cd_perimeter = new Array();
    cd_perimeter_scaled = [];
    //console.log("resetPolygonVariables called...");
}

function canvasClick(event)
{
    if (canvasDrawMode)
    {
        var rect = cd_canvas.getBoundingClientRect();
        var x = event.clientX - rect.left;
        var y = event.clientY - rect.top;
        if (viewportCurrentTab == 1)
        {
            if ((x >= (WidthValid + centerShift_x) || (x <= centerShift_x)) || (y >= (HeightValid + centerShift_y) || (y <= centerShift_y)))
            {
                alert('Canvas drawing operations can only be performed within the valid draw area');
                canvasDrawMode = false;
                cd_complete = false;
                resetPolygonVariables();
                BackEndUISync(0, project_state);
            }
            else
            {
                if ((cursorCurrentMode == "celldraw") || (cursorCurrentMode == "ldmkdraw") || (cursorCurrentMode =="checkroi"))
                {
                    [done,cd_perimeter,cd_perimeter_scaled, reset] = cd_point_it(event, cd_canvas, cd_complete, cd_perimeter, cd_perimeter_scaled, DVPImageWidth, DVPImageHeight, viewportCurrentTab);
                    if (done)
                    {
                        if (cursorCurrentMode == "celldraw")
                        {
                            UActionPayloadSC("celldraw",["tablJSON",10000000,JSON.stringify(cd_perimeter_scaled)],"canvasClick");
                        }
                        if (cursorCurrentMode == "ldmkdraw")
                        {
                            UActionPayloadSC("ldmkdraw",["tablJSON",10000000,JSON.stringify(cd_perimeter_scaled)],"canvasClick");
                        }
                        if (cursorCurrentMode == "checkroi")
                        {
                            UActionPayloadSC("checkroi",["tablJSON",10000000,JSON.stringify(cd_perimeter_scaled)],"canvasClick");
                        }
                        resetPolygonVariables();
                        return;
                    }
                    if (reset){resetPolygonVariables();}
                }
                else
                {
                    resetPolygonVariables();
                    corrected_x = x - ((rect.width - DVPImageWidth)/2);
                    corrected_y = y - ((rect.height - DVPImageHeight)/2);
                    corrected_x = corrected_x/DVPImageWidth;
                    corrected_y = corrected_y/DVPImageHeight;
                    if (cursorCurrentMode == "cellremove")
                    {
                        user_defined_name = "";
                        UActionPayloadSC("cellremv",["tablJSON",10000000,JSON.stringify({corrected_x,corrected_y,user_defined_name})],"canvasClick");
                    }
                    if (cursorCurrentMode == "landmarkremove")
                    {
                        user_defined_name = "";
                        UActionPayloadSC("ldmkremv",["tablJSON",10000000,JSON.stringify({corrected_x,corrected_y,user_defined_name})],"canvasClick");
                    }
                    if (cursorCurrentMode == "cellname")
                    {
                        UActionPayloadSC("cellname",["tablJSON",10000000,JSON.stringify({corrected_x,corrected_y,user_defined_name})],"canvasClick");
                    }
                    if (cursorCurrentMode == "ldmkname")
                    {
                        UActionPayloadSC("ldmkname",["tablJSON",10000000,JSON.stringify({corrected_x,corrected_y,user_defined_name})],"canvasClick");
                    }
                    if (cursorCurrentMode == "viewfavo")
                    {
                        user_defined_name = "";
                        UActionPayloadSC("viewfavo",["tablJSON",10000000,JSON.stringify({corrected_x,corrected_y,user_defined_name})],"canvasClick");
                    }
                    if (cursorCurrentMode == "deleRest")
                    {
                        user_defined_name = "";
                        UActionPayloadSC("deleRest",["tablJSON",10000000,JSON.stringify({corrected_x,corrected_y,user_defined_name})],"canvasClick");
                    }
                }
            }
        }
        if (viewportCurrentTab == 2)
        {     
            if ((x >= DVPImageWidth || (x <= 0)) || (y >= (rect.height - ((rect.height - DVPImageHeight)/2)) || (y <= (rect.height - DVPImageHeight)/2)))
            {
                alert('Canvas drawing operations can only be performed within the valid draw area! In dual viewport mode, this means only the left viewport.');
                canvasDrawMode = false;
                cd_complete = false;
                resetPolygonVariables();
                BackEndUISync(0, project_state);
            }
            else
            {
                if ((cursorCurrentMode == "celldraw") || (cursorCurrentMode == "ldmkdraw") || (cursorCurrentMode =="checkroi"))
                {
                    [done,cd_perimeter,cd_perimeter_scaled, reset] = cd_point_it(event, cd_canvas, cd_complete, cd_perimeter, cd_perimeter_scaled, DVPImageWidth, DVPImageHeight, viewportCurrentTab);
                    if (done)
                    {
                        if (cursorCurrentMode == "celldraw")
                        {
                            UActionPayloadSC("celldraw",["tablJSON",10000000,JSON.stringify(cd_perimeter_scaled)],"canvasClick");
                        }
                        if (cursorCurrentMode == "ldmkdraw")
                        {
                            UActionPayloadSC("ldmkdraw",["tablJSON",10000000,JSON.stringify(cd_perimeter_scaled)],"canvasClick");
                        }
                        if (cursorCurrentMode == "checkroi")
                        {
                            UActionPayloadSC("checkroi",["tablJSON",10000000,JSON.stringify(cd_perimeter_scaled)],"canvasClick");
                        }
                        resetPolygonVariables();
                        return;
                    }
                    if (reset) {resetPolygonVariables();}
                }
                else
                {
                    resetPolygonVariables();
                    corrected_x = x;
                    corrected_y = y - ((rect.height - DVPImageHeight)/2);
                    corrected_x = corrected_x/DVPImageWidth;
                    corrected_y = corrected_y/DVPImageHeight;
                    if (cursorCurrentMode == "cellremove")
                    {
                        user_defined_name = "";
                        UActionPayloadSC("cellremv",["tablJSON",10000000,JSON.stringify({corrected_x,corrected_y,user_defined_name})],"canvasClick");
                    }
                    if (cursorCurrentMode == "landmarkremove")
                    {
                        user_defined_name = "";
                        UActionPayloadSC("ldmkremv",["tablJSON",10000000,JSON.stringify({corrected_x,corrected_y,user_defined_name})],"canvasClick");
                    }
                    if (cursorCurrentMode == "cellname")
                    {
                        UActionPayloadSC("cellname",["tablJSON",10000000,JSON.stringify({corrected_x,corrected_y,user_defined_name})],"canvasClick");
                    }
                    if (cursorCurrentMode == "ldmkname")
                    {
                        UActionPayloadSC("ldmkname",["tablJSON",10000000,JSON.stringify({corrected_x,corrected_y,user_defined_name})],"canvasClick");
                    }
                    if (cursorCurrentMode == "viewfavo")
                    {
                        user_defined_name = "";
                        UActionPayloadSC("viewfavo",["tablJSON",10000000,JSON.stringify({corrected_x,corrected_y,user_defined_name})],"canvasClick");
                    }
                    if (cursorCurrentMode == "deleRest")
                    {
                        user_defined_name = "";
                        UActionPayloadSC("deleRest",["tablJSON",10000000,JSON.stringify({corrected_x,corrected_y,user_defined_name})],"canvasClick");
                    }
                }
            }
        }
        return;
    }
    resetPolygonVariables();
}

function UIR_DRLLdMarkAdd()
{
    if (!document.getElementById("DRLLdMarkAdd").disabled) //Process only if this button is enabled
    {
        if (!waitingOnServer)
        {
            if (confirm("You are about to enter polygon draw mode. You will be limited to the viewport (in single mode) or the left viewport (in dual mode). Left click to define polygon points, right click to finish and close the polygon."))
            {
                cursorCurrentMode = "ldmkdraw";
                polygonDrawMode();
            }
        }
    } else{toast("This function is currently disabled...");}
}

function UIR_ExtractRIOButton()
{
    if (!document.getElementById("UIR_ExtractRIOButton").disabled) //Process only if this button is enabled
    {
        if (!waitingOnServer)
        {
            if (confirm("You are about to enter polygon draw mode. You will be limited to the viewport (in single mode) or the left viewport (in dual mode). Left click to define polygon points, right click to finish and close the polygon."))
            {
                cursorCurrentMode = "checkroi";
                polygonDrawMode();
            }
        }
    } else{toast("This function is currently disabled...");}
}

function UIR_DRLLdMarkRemove()
{
    if (!document.getElementById("DRLLdMarkRemove").disabled) //Process only if this button is enabled
    {
        if (!waitingOnServer)
        {
            if (confirm("You are about to enter cursor click mode. Just left click within the image. You will be limited to the viewport (in single mode) or the left viewport (in dual mode)."))
            {
                cursorCurrentMode = "landmarkremove";
                polygonDrawMode();
            }
        }
    } else{toast("This function is currently disabled...");}
}

function UIR_DRLCellRemove()
{
    if (!document.getElementById("DRLCellRemove").disabled) //Process only if this button is enabled
    {
        if (!waitingOnServer)
        {
            if (confirm("You are about to enter cursor click mode. Just left click within the image. You will be limited to the viewport (in single mode) or the left viewport (in dual mode)."))
            {
                cursorCurrentMode = "cellremove";
                polygonDrawMode();
            }
        }
    } else{toast("This function is currently disabled...");}
}

function UIR_DRLCellName()
{
    if (!document.getElementById("DRLCellName").disabled) //Process only if this button is enabled
    {
        if (!waitingOnServer)
        {
            let text;
            var characters = /^[0-9a-zA-Z_-]+$/;
            user_defined_name = prompt("Enter the name you'd like to assign to your chosen cell boundary. After you enter the name, you will enter cursor click mode. Left click on a position within viewport (single mode) or left viewport (dual mode).", "BD".concat(makeid(7)));
            if (!(user_defined_name == null || user_defined_name == ""))
            {
                if (characters.test(user_defined_name))
                {
                    cursorCurrentMode = "cellname";
                    polygonDrawMode();
                }
                else
                {
                    alert('Illegal name: Please input alphanumeric characters only. No spaces or special characters.');
                }
            }
        }
    } else{toast("This function is currently disabled...");}
}

function UIR_DRLLdMarkName()
{
    if (!document.getElementById("DRLLdMarkName").disabled) //Process only if this button is enabled
    {
        if (!waitingOnServer)
        {
            let text;
            var characters = /^[0-9a-zA-Z_-]+$/;
            user_defined_name = prompt("Enter the name you'd like to assign to your chosen landmark boundary. After you enter the name, you will enter cursor click mode. Left click on a position within viewport (single mode) or left viewport (dual mode).", "BD".concat(makeid(7)));
            if (!(user_defined_name == null || user_defined_name == ""))
            {
                if (characters.test(user_defined_name))
                {
                    cursorCurrentMode = "ldmkname"
                    polygonDrawMode();
                }
                else
                {
                    alert('Illegal name: Please input alphanumeric characters only. No spaces or special characters.');
                }
            }
        }
    } else{toast("This function is currently disabled...");}
}

function UIR_DRLCellSave()
{
    if (!document.getElementById("DRLCellSave").disabled) //Process only if this button is enabled
    {
        if (!waitingOnServer)
        {
            let text;
            var characters = /^[0-9a-zA-Z_-]+$/;
            let user_defined_suffix = prompt("AQuA2-Cloud will save all defined cell regions to a .mat file that AQuA2-Cloud and MATLAB AQuA2 can use.", "_AQuA2_".concat(makeid(7)));
            if (!(user_defined_suffix == null || user_defined_suffix == ""))
            {
                if (characters.test(user_defined_suffix))
                {
                    UActionGetUserPath("getCurrentUserPath", user_defined_suffix, "UIR_DRLCellSave");
                }
                else
                {
                    alert('Illegal filename: Please input alphanumeric characters only. No spaces or special characters.');
                }
            }
        }
    } else{toast("This function is currently disabled...");}
}

function UIR_DRLLdMarkSave()
{
    if (!document.getElementById("DRLLdMarkSave").disabled) //Process only if this button is enabled
    {
        if (!waitingOnServer)
        {
            let text;
            var characters = /^[0-9a-zA-Z_-]+$/;
            let user_defined_suffix = prompt("AQuA2-Cloud will save all defined landmark regions to a .mat file that AQuA2-Cloud and MATLAB AQuA2 can use.", "_AQuA2_".concat(makeid(7)));
            if (!(user_defined_suffix == null || user_defined_suffix == ""))
            {
                if (characters.test(user_defined_suffix))
                {
                    UActionGetUserPath("getCurrentUserPath", user_defined_suffix, "UIR_DRLLdMarkSave");
                }
                else
                {
                    alert('Illegal filename: Please input alphanumeric characters only. No spaces or special characters.');
                }
            }
        }
    } else
    {
        toast("This function is currently disabled...");
    }
}

function UIR_FavouritesTabSaveWavesButton()
{
    if (!document.getElementById("UIR_FavouritesTabSaveWavesButton").disabled) //Process only if this button is enabled
    {
        if (!waitingOnServer)
        {
            if (confirm("AQuA2-Cloud will save intensity curves for all selected events to a .csv file that AQuA2-Cloud and MATLAB AQuA2 can use. The .csv files will be saved to a folder that is the name of your source data, with 2 subfolders containing curves for the event durations, and the entire movie duration, respectively."))
            {
                UActionGetUserPath("getCurrentUserPath", "", "UIR_FavouritesTabSaveWavesButton");
            }
        }
    } else{toast("This function is currently disabled...");}
}

function UIR_DRLCellLoad()
{
    if (!document.getElementById("DRLCellLoad").disabled) //Process only if this button is enabled
    {
        if (!waitingOnServer)
        {
            if (referencedFileFull)
            {
                if (isMAT(referencedFileFull))
                {
                    if (confirm("The instance will attempt to load the currently referenced cell boundaries .MAT."))
                    {
                        toast("Attempting to load cell boundary data...");
                        UActionPayloadSC("cellload",["location",10000000,referencedFileFull],"UIR_DRLCellLoad");
                    }
                }
                else
                {
                    toast("Referenced file is not of .mat file type...");
                }
            }
            else
            {
                toast("You have not referenced any .mat files...");
            }
        }
    } else{toast("This function is currently disabled...");}
}

function UIR_DRLLdMarkLoad()
{
    if (!document.getElementById("DRLLdMarkLoad").disabled) //Process only if this button is enabled
    {
        if (!waitingOnServer)
        {
            if (referencedFileFull)
            {
                if (isMAT(referencedFileFull))
                {
                    if (confirm("The instance will attempt to load the currently referenced landmark boundaries .MAT."))
                    {
                        toast("Attempting to load landmark boundary data...");
                        UActionPayloadSC("ldmkload",["location",10000000,referencedFileFull],"UIR_DRLLdMarkLoad");
                    }
                }
                else
                {
                	toast("Referenced file is not of .mat file type...");
                }
            }
            else
            {
                toast("You have not referenced any .mat files...");
            }
        }
    } else{toast("This function is currently disabled...");}
}

function UIR_updateFeaturesButton()
{
    if (!document.getElementById("UIR_updateFeaturesButton").disabled) //Process only if this button is enabled
    {
        if (!waitingOnServer)
        {
            UActionPayloadSC("cmdLUpFe",[],"UIR_updateFeaturesButton");
        }
    } else{toast("This function is currently disabled...");}
}

function UIR_MoviePanelSingleFrameRightButton()
{
    //Only execute if the button is not disabled
    if (!(document.getElementById("MoviePanelSingleFrameRightButton").disabled))
    {
        if (!waitingOnServer)
        {
            if ((parseFloat(document.getElementById("MoviePanelFrameSlider").value)) < parseFloat(document.getElementById("MoviePanelFrameSlider").max))
            {
                document.getElementById("MoviePanelFrameSlider").value = String((Math.round(((parseFloat(document.getElementById("MoviePanelFrameSlider").value)) + Number.EPSILON + 1) * 1))/ 1);
                BackEndUISync(0, project_state);
            }
            else {toast("Cannot go past last frame");}
        }
    } else {toast("This function is currently disabled...");}
}

function UIR_MoviePanelSingleFrameLeftButton()
{
    //Only execute if the button is not disabled
    if (!(document.getElementById("MoviePanelSingleFrameLeftButton").disabled))
    {
        if (!waitingOnServer)
        {
            if ((parseFloat(document.getElementById("MoviePanelFrameSlider").value)) >= 2)
            {
                document.getElementById("MoviePanelFrameSlider").value = String((Math.round(((parseFloat(document.getElementById("MoviePanelFrameSlider").value)) - Number.EPSILON - 1) * 1))/ 1);
                BackEndUISync(0, project_state);
            } else {toast("Cannot go past initial frame");}
        }
    } else {toast("This function is currently disabled...");}
}

$("#MoviePanelFrameSlider").on('change', function()
{
    if (!(document.getElementById("MoviePanelFrameSlider").disabled))
    {
        if (!waitingOnServer)
        {
            document.getElementById("MoviePanelFrameSlider").value = String((Math.round(((parseFloat(document.getElementById("MoviePanelFrameSlider").value)) + Number.EPSILON) * 1))/ 1);
            BackEndUISync(0, project_state);
        }
    } else {toast("This function is currently disabled...");}
});

$("#MoviePanelJumpToInput").on('change paste', function()
{
    if (custom_isNumeric.test(document.getElementById("MoviePanelJumpToInput").value)) //True if input is a valid number
    {
        if (((parseFloat(document.getElementById("MoviePanelJumpToInput").value)) % 1) == 0) //True if number is whole number
        {
            if ((parseInt(document.getElementById("MoviePanelJumpToInput").value)) > 0) //True if whole number is greater than zero
            {
                if ((parseInt(document.getElementById("MoviePanelJumpToInput").value)) <= movieTotalFrameCount)
                {
                    document.getElementById("MoviePanelFrameSlider").value = document.getElementById("MoviePanelJumpToInput").value;
                    BackEndUISync(0, project_state);
                    return;
                }
            }
        }
    }
    document.getElementById("MoviePanelJumpToInput").value = movieCurrentFrame;
});

$("#Viewport1DSValue").on('change', function()
{
    if (!(document.getElementById("Viewport1DSValue").disabled))
    {
        if (!waitingOnServer)
        {
            if (isNaN(document.getElementById("Viewport1DSValue").value))
            {
                toast("Numerical values only...");
            }
            else
            {
                if ((document.getElementById("Viewport1DSValue").value >= 0.1) && (document.getElementById("Viewport1DSValue").value <= 1))
                {
                    BackEndUISync(0, project_state);
                }
                else
                {
                    toast("Only values between 0.1 and 1 are accepted...");
                }
            }
        }
    } else {toast("This function is currently disabled...");}
});

$("#Viewport2LDSValue").on('change', function()
{
    if (!(document.getElementById("Viewport2LDSValue").disabled))
    {
        if (!waitingOnServer)
        {
            if (isNaN(document.getElementById("Viewport2LDSValue").value))
            {
                toast("Numerical values only...");
            }
            else
            {
                if ((document.getElementById("Viewport2LDSValue").value >= 0.1) && (document.getElementById("Viewport2LDSValue").value <= 1))
                {
                    BackEndUISync(0, project_state);
                }
                else
                {
                    toast("Only values between 0.1 and 1 make are accepted...");
                }
            }
        }
    } else {toast("This function is currently disabled...");}
});

$("#Viewport2RDSValue").on('change', function()
{
    if (!(document.getElementById("Viewport2RDSValue").disabled))
    {
        if (!waitingOnServer)
        {
            if (isNaN(document.getElementById("Viewport2RDSValue").value))
            {
                toast("Numerical values only...");
            }
            else
            {
                if ((document.getElementById("Viewport2RDSValue").value >= 0.1) && (document.getElementById("Viewport2RDSValue").value <= 1))
                {
                    BackEndUISync(0, project_state);
                }
                else
                {
                    toast("Only values between 0.1 and 1 are accepted...");
                }
            }
        }
    } else {toast("This function is currently disabled...");}
});

$("#CurvePlotDSValue").on('change', function()
{
    if (!(document.getElementById("CurvePlotDSValue").disabled))
    {
        if (!waitingOnServer)
        {
            if (isNaN(document.getElementById("CurvePlotDSValue").value))
            {
                toast("Numerical values only...");
            }
            else
            {
                if ((document.getElementById("CurvePlotDSValue").value >= 0.1) && (document.getElementById("CurvePlotDSValue").value <= 1))
                {
                    BackEndUISync(0, project_state);
                }
                else
                {
                    toast("Only values between 0.1 and 1 are accepted...");
                }
            }
        }
    } else {toast("This function is currently disabled...");}
});

function UIR_DSQualityButtonLow()
{
    if (!(document.getElementById("DSQualityButtonLow").disabled))
    {
        if (!waitingOnServer)
        {
            document.getElementById("Viewport1DSValue").value = 0.5;
            document.getElementById("Viewport2LDSValue").value = 0.5;
            document.getElementById("Viewport2RDSValue").value = 0.5;
            document.getElementById("CurvePlotDSValue").value = 0.5;
            BackEndUISync(0, project_state);
        }
    } else {toast("This function is currently disabled...");}
}

function UIR_DSQualityButtonMed()
{
    if (!(document.getElementById("DSQualityButtonMed").disabled))
    {
        if (!waitingOnServer)
        {
            document.getElementById("Viewport1DSValue").value = 0.75;
            document.getElementById("Viewport2LDSValue").value = 0.75;
            document.getElementById("Viewport2RDSValue").value = 0.75;
            document.getElementById("CurvePlotDSValue").value = 0.75;
            BackEndUISync(0, project_state);
        }
    } else {toast("This function is currently disabled...");}
}

function UIR_DSQualityButtonFull()
{
    if (!(document.getElementById("DSQualityButtonFull").disabled))
    {
        if (!waitingOnServer)
        {
            document.getElementById("Viewport1DSValue").value = 1;
            document.getElementById("Viewport2LDSValue").value = 1;
            document.getElementById("Viewport2RDSValue").value = 1;
            document.getElementById("CurvePlotDSValue").value = 1;
            BackEndUISync(0, project_state);
        }
    } else {toast("This function is currently disabled...");}
}

$("#ExportTabProjectToggle").on('change paste', function()
{
    if (!waitingOnServer)
    {
        BackEndUISync(0, project_state);
    }
});

$("#ExportTabFeatureTableToggle").on('change paste', function()
{
    if (!waitingOnServer)
    {
        BackEndUISync(0, project_state);
    }
});

$("#ExportTabMovieWithOverlayToggle").on('change paste', function()
{
    if (!waitingOnServer)
    {
        BackEndUISync(0, project_state);
    }
});

function UIR_ExportTabExportButton()
{
    if (!document.getElementById("ExportTabExportButton").disabled) //Process only if this button is enabled
    {
        if (!waitingOnServer)
        {
            let text;
            var characters = /^[0-9a-zA-Z_-]+$/;
            let user_defined_suffix = prompt("AQuA2-Cloud will export elements of the current instance project to their respective files as defined by the above toggled options. The suffix will be appended to the file name.", "_AQuA2_".concat(makeid(7)));
            if (!(user_defined_suffix == null || user_defined_suffix == ""))
            {
                if (characters.test(user_defined_suffix))
                {
                    UActionGetUserPath("getCurrentUserPath", user_defined_suffix, "UIR_ExportTabExportButton");
                }
                else
                {
                    alert('Illegal filename: Please input alphanumeric characters only. No spaces or special characters.');
                }
            }
        }
    } else{toast("This function is currently disabled...");}
}

function UIR_ProofReadingViewFavButton()
{
    if (!document.getElementById("UIR_ProofReadingViewFavButton").disabled) //Process only if this button is enabled
    {
        if (!waitingOnServer)
        {
            if (confirm("Left click to select a event in the viewport (single mode) or the left viewport (in dual mode). The event will be added to the favourites table and its curve will be shown in the curve plot."))
            {
                cursorCurrentMode = "viewfavo";
                polygonDrawMode();
            }
        }
    } else{toast("This function is currently disabled...");}
}

function UIR_ProofReadingDeleteRestoreButton()
{
    if (!document.getElementById("UIR_ProofReadingDeleteRestoreButton").disabled) //Process only if this button is enabled
    {
        if (!waitingOnServer)
        {
            if (confirm("Left click to select a event in the viewport (single mode) or the left viewport (in dual mode). Select an event for which you'd like to toggle the visibility of its corresponding color pixels within the optical field. This is useful if you have multiple overlapping events and would like to hide some events to reveal others. Note that this does not delete an event entirely. Use the checkboxes and delete button within the favourites panel to delete events from the favourites table."))
            {
                cursorCurrentMode = "deleRest";
                polygonDrawMode();
            }
        }
    } else{toast("This function is currently disabled...");}
}

function UIR_ProofReadingAddAllFilteredButton()
{
    if (!document.getElementById("UIR_ProofReadingAddAllFilteredButton").disabled) //Process only if this button is enabled
    {
        if (!waitingOnServer)
        {
            UActionPayloadSC("AddAllFl",[],"UIR_ProofReadingAddAllFilteredButton");
        }
    } else{toast("This function is currently disabled...");}
}

function UIR_FavouritesTabSelectAllButton()
{
    if (!document.getElementById("UIR_FavouritesTabSelectAllButton").disabled)
    {
        if (!waitingOnServer)
        {
            UActionPayloadSC("selecAll",[],"UIR_FavouritesTabSelectAllButton");
        }
    } else{toast("This function is currently disabled...");}
}

function UIR_FavouritesTabDeleteButton()
{
    if (!document.getElementById("UIR_FavouritesTabDeleteButton").disabled)
    {
        if (!waitingOnServer)
        {
            UActionPayloadSC("seldelet",[],"UIR_FavouritesTabDeleteButton");
        }
    } else{toast("This function is currently disabled...");}
}

function UIR_FavouritesTabShowCurvesButton()
{
    if (!document.getElementById("UIR_FavouritesTabShowCurvesButton").disabled) //Process only if this button is enabled
    {
        if (!waitingOnServer)
        {
            UActionPayloadSC("showCurv",[],"UIR_FavouritesTabShowCurvesButton");
        }
    } else{toast("This function is currently disabled...");}
}

function UIR_toolsAddEvt1Button()
{
    if (!document.getElementById("UIR_toolsAddEvt1Button").disabled) //Process only if this button is enabled
    {
        if (!waitingOnServer)
        {
            if (!isNaN(document.getElementById("UIR_toolsAddEvt1").value))
            {
                if (((parseFloat(document.getElementById("UIR_toolsAddEvt1").value)) % 1) == 0) //True if number is whole number
                {
                    if ((parseInt(document.getElementById("UIR_toolsAddEvt1").value)) > 0) //True if whole number is greater than zero
                    {
                        UActionPayloadSC("maddEvt1",["maddEvt1",10000000,document.getElementById("UIR_toolsAddEvt1").value],"UIR_toolsAddEvt1Button");
                        return;
                    }
                }
            }
            toast("Please enter a valid number greater than 0...");
        }
    } else{toast("This function is currently disabled...");}
}

function UIR_toolsAddEvt2Button()
{
    if (!document.getElementById("UIR_toolsAddEvt2Button").disabled) //Process only if this button is enabled
    {
        if (!waitingOnServer)
        {
            if (!isNaN(document.getElementById("UIR_toolsAddEvt2").value))
            {
                if (((parseFloat(document.getElementById("UIR_toolsAddEvt2").value)) % 1) == 0) //True if number is whole number
                {
                    if ((parseInt(document.getElementById("UIR_toolsAddEvt2").value)) > 0) //True if whole number is greater than zero
                    {
                        UActionPayloadSC("maddEvt2",["maddEvt2",10000000,document.getElementById("UIR_toolsAddEvt2").value],"UIR_toolsAddEvt2Button");
                        return;
                    }
                }
            }
            toast("Please enter a valid number greater than 0...");
        }
    } else{toast("This function is currently disabled...");}
}