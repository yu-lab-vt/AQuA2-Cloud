function BEUISync(obj,conn,message)
    %If project state = 0, this function will never be called
    %Retrieve fh parameters dependent on the current project state
    global f;
    global projectState
    global im1DownSample;
    global im2aDownSample;
    global im2bDownSample;
    global graphCurveDownSample;

    adjMovFlag = 0;
    sbsChange = 0;
    movViewSelChange = 0;

    fh = guidata(f);

    try
        if (projectState == 1) %Project state == 1 means the AQuA WBSv Backend instance is completely new and uninitialized
            deconstructedData = Network.fromStreamPayload(obj,conn,message);
            %We can make synchronizing FE UI values to Backend UI trivial if we
            %keep same position of UI elements in sync array
            % NewProjTabCh1FileDirInput -> deconstructedData{5}
            % NewProjTabCh2FileDirInput -> deconstructedData{8}
            % NewProjTabDataTypeDropDown -> {11}
            % NewProjTabTempResInput {14}
            % NewProjTabSpatialResInputInput {17}
            % NewProjTabExcludePixelsInput {20}
            % LayersMinSlider {23}
        
            fh.fIn1.Value = deconstructedData{5};
            if ((isempty(fh.fIn1.Value)) == 0)
                fh.fIn2.Value = deconstructedData{8};
            else
                fh.fIn2.Value = ''; %Set 2nd field empty
            end
            %{11}, the preset dropdown data, is special case and is handled
            %seperately from this function
            fh.tmpRes.Value = deconstructedData{14}; %Temporal resolution (Seconds per frame)
            fh.spaRes.Value = deconstructedData{17}; %Spatial resolution (um per frame)
            fh.bdSpa.Value =  deconstructedData{20}; %Border pixel exclusion (exlude this number of pixels from borders)
            %%Supervisor.FEUISync(obj,conn,message);
        
        
        
        %     responseFhValueArray = {["projStat"] [10000000] [projectState]...
        %         ["xxxxfIn1"] [10000000] [fh.fIn1.String]...
        %         ["xxxxfIn2"] [10000000] [fh.fIn2.String]...
        %         ["xxpreset"] [10000000] [fh.preset.Value]...
        %         ["xxtmpRes"] [10000000] [fh.tmpRes.String]...
        %         ["xxspaRes"] [10000000] [fh.spaRes.String]...
        %         ["xxxbdSpa"] [10000000] [fh.bdSpa.String]};
        end
        if (projectState == 2)
            %fprintf("debug _______________");
            deconstructedData = Network.fromStreamPayload(obj,conn,message);
            if (fh.sldMin.Value ~= str2double(deconstructedData{23}))
                fh.sldMin.Value = str2double(deconstructedData{23});
                adjMovFlag = 1;
            end
            if (fh.sldMax.Value ~= str2double(deconstructedData{26}))
                fh.sldMax.Value = str2double(deconstructedData{26});
                adjMovFlag = 1;
            end
            if (fh.sldBri1.Value ~= str2double(deconstructedData{29}))
                fh.sldBri1.Value = str2double(deconstructedData{29});
                fh.sldBriL.Value = fh.sldBri1.Value;
                adjMovFlag = 1;
            end
            if (fh.sldBriR.Value ~= str2double(deconstructedData{32}))
                fh.sldBriR.Value = str2double(deconstructedData{32});
                adjMovFlag = 1;
            end
            if (fh.sldMov.Value ~= str2double(deconstructedData{35}))
                fh.sldMov.Value = str2double(deconstructedData{35});
                ui.mov.stepOne();
            end

            if (fh.sldMin.Value ~= str2double(deconstructedData{23}))
                fh.sldMin.Value = str2double(deconstructedData{23});
                adjMovFlag = 1;
            end

            if (fh.sldBriOv.Value ~= str2double(deconstructedData{110}))
                fh.sldBriOv.Value = str2double(deconstructedData{110});
                adjMovFlag = 1;
            end
            fh.registrateCorrect.Value = fh.registrateCorrect.Items(str2num(deconstructedData{38}) + 1);
            fh.bleachCorrect.Value = fh.bleachCorrect.Items(str2num(deconstructedData{41}) + 1);

            %Modified to medSmo from smoXY, medSmo is "median filter
            %radius"
            if (fh.medSmo.Value ~= deconstructedData{44})
                fh.medSmo.Value = deconstructedData{44};
            end
            %Modified to smoXY
            if (fh.smoXY.Value ~= deconstructedData{47})
                fh.smoXY.Value = deconstructedData{47};
            end
            if (fh.minDur.Value ~= deconstructedData{53})
                fh.minDur.Value = deconstructedData{53};
            end
            if (fh.minSize.Value ~= deconstructedData{56})
                fh.minSize.Value = deconstructedData{56};
            end
            if (fh.maxSize.Value ~= deconstructedData{59})
                fh.maxSize.Value = deconstructedData{59};
            end
            if (fh.circularityThr.Value ~= deconstructedData{62})
                fh.circularityThr.Value = deconstructedData{62};
            end
        
            if strcmp(deconstructedData{65},'true')
                if (fh.needTemp.Value ~= 1)
                    fh.needTemp.Value = 1;
                end
            else
                fh.needTemp.Value = 0;
            end
            if (fh.sigThr.Value ~= deconstructedData{68}) %Zscore of seed significance
                fh.sigThr.Value = deconstructedData{68};
            end
        
            if (fh.maxDelay.Value ~= deconstructedData{71}) %Allowed maximum dissimilarity in merging
                fh.maxDelay.Value = deconstructedData{71};
            end
			
            if (fh.seedSzRatio.Value ~= deconstructedData{152}) %Allowed maximum dissimilarity in merging
                fh.seedSzRatio.Value = deconstructedData{152};
            end
        
            if strcmp(deconstructedData{74},'true') %Refine temporally adjacent peaks
                if (fh.needRefine.Value ~= 1)
                    fh.needRefine.Value = 1;
                end
            else
                fh.needRefine.Value = 0;
            end
			
            if strcmp(deconstructedData{149},'true') %Temporal Extension Toggle
                if (fh.whetherExtend.Value ~= 1)
                    fh.whetherExtend.Value = 1;
                end
            else
                fh.whetherExtend.Value = 0;
            end
			
			%%%%%%%%%%%%%%%%%%%
			
            if strcmp(deconstructedData{152},'true') %Propagation Metric Toggle
                if (fh.propMetric.Value ~= 1)
                    fh.propMetric.Value = 1;
                end
            else
                fh.propMetric.Value = 0;
            end

            if strcmp(deconstructedData{155},'true') %Prop Metric Toggle
                if (fh.propMetric.Value ~= 1)
                    fh.propMetric.Value = 1;
                end
            else
                fh.propMetric.Value = 0;
            end

			
            if strcmp(deconstructedData{158},'true') %Network Features Toggle
                if (fh.networkFeatures.Value ~= 1)
                    fh.networkFeatures.Value = 1;
                end
            else
                fh.networkFeatures.Value = 0;
            end
			
			%%%%%%%%%%%%%%%
        
            if strcmp(deconstructedData{77},'true')
                if (fh.needGrow.Value ~= 1)
                    fh.needGrow.Value = 1;
                end
            else
                fh.needGrow.Value = 0;
            end
        
            %%Note: A detect pipeline tab change requires special behaviour.
            if (fh.deOutTab.SelectedTab ~= fh.deOutTab.Children(str2num(deconstructedData{80})))
                fh.deOutTab.SelectedTab = fh.deOutTab.Children(str2num(deconstructedData{80}));
                ui.detect.flow('chg');
            end
        
            if strcmp(deconstructedData{83},'true')
                if (fh.needSpa.Value ~= 1)
                    fh.needSpa.Value = 1;
                end
            else
                fh.needSpa.Value = 0;
            end
			
            if strcmp(deconstructedData{149},'true')
                if (fh.whetherExtend.Value ~= 1)
                    fh.whetherExtend.Value = 1;
                end
            else
                fh.whetherExtend.Value = 0;
            end
        
            if (fh.sourceSzRatio.Value ~= deconstructedData{86}) %Source size relative to super event
                fh.sourceSzRatio.Value = deconstructedData{86};
            end
        
            if (fh.sourceSensitivity.Value ~= deconstructedData{89}) %Source size relative to super event
                fh.sourceSensitivity.Value = deconstructedData{89};
            end
			
        
            %
            %{92} is deprecated
            %
        
            if strcmp(deconstructedData{95},'true')
                if (fh.detectGlo.Value ~= 1)
                    fh.detectGlo.Value = 1;
                end
            else
                fh.detectGlo.Value = 0;
            end
        
            if strcmp(deconstructedData{98},'true')
                if (fh.ignoreTau.Value ~= 1)
                    fh.ignoreTau.Value = 1;
                end
            else
                fh.ignoreTau.Value = 0;
            end
        
            if strcmp(deconstructedData{101},'true')
                if (fh.expEvt.Value ~= 1)
                    fh.expEvt.Value = 1;
                end
            else
                fh.expEvt.Value = 0;
            end
        
            if strcmp(deconstructedData{104},'true')
                if (fh.expFt.Value ~= 1)
                    fh.expFt.Value = 1;
                end
            else
                fh.expFt.Value = 0;
            end
        
            if strcmp(deconstructedData{107},'true')
                if (fh.expMov.Value ~= 1)
                    fh.expMov.Value = 1;
                end
            else
                fh.expMov.Value = 0;
            end

            fh.overlayDat.Value = fh.overlayDat.Items(str2num(deconstructedData{113}) + 1);
            fh.overlayFeature.Value = fh.overlayFeature.Items(str2num(deconstructedData{116}) + 1);
            fh.overlayColor.Value = fh.overlayColor.Items(str2num(deconstructedData{119}) + 1);
			
            fh.channelOptionL.Value = fh.channelOptionL.Items(str2num(deconstructedData{131}) + 1);
            fh.channelOptionR.Value = fh.channelOptionR.Items(str2num(deconstructedData{134}) + 1);
			
            if (im1DownSample ~= str2double(deconstructedData{137}))
                im1DownSample = str2double(deconstructedData{137});
            end
			
            if (im2aDownSample ~= str2double(deconstructedData{140}))
                im2aDownSample = str2double(deconstructedData{140});
            end
			
            if (im2bDownSample ~= str2double(deconstructedData{143}))
                im2bDownSample = str2double(deconstructedData{143});
            end
			
            if (graphCurveDownSample ~= str2double(deconstructedData{146}))
                graphCurveDownSample = str2double(deconstructedData{146});
            end
            
            if (fh.sbs.Value ~= (str2num(deconstructedData{122}) - 1))
                if (str2num(deconstructedData{122}) == 1)
                    fh.sbs.Value = 0;
                else
                    fh.sbs.Value = 1;
                end
                sbsChange = 1;
            end
			
			
			if (strcmp(fh.movLType.Value,fh.movLType.Items{str2num(deconstructedData{125}) + 1}) | strcmp(fh.movRType.Value,fh.movRType.Items{str2num(deconstructedData{128}) + 1}))
				fh.movLType.Value = fh.movLType.Items(str2num(deconstructedData{125}) + 1);
				fh.movRType.Value = fh.movRType.Items(str2num(deconstructedData{128}) + 1);
				movViewSelChange = 1;
			end
			
            if (fh.thrArScl.Value ~= deconstructedData{50}) %Intensity threshold scaling factor [number]
				ui.mov.thrAdjust(str2double(deconstructedData{50}));
                %fh.thrArScl.Value = deconstructedData{50};
				%if (str2double(deconstructedData{50}) >= fh.sldActThr.Limits(1)) && (str2double(deconstructedData{50}) <= fh.sldActThr.Limits(2))
				%	fh.sldActThr.Value = str2double(deconstructedData{50});
				%end
            end

            if (sbsChange)
                ui.mov.movSideBySide();
            end

            if (adjMovFlag)
                ui.over.adjMov();
            end
			
			if (movViewSelChange)
				ui.mov.movViewSel([],[],f);
			end
            %Supervisor.FEUISync(obj,conn,message);
        end
        
        % argumentArray = {["fIn1"] [10000000] [fh.fIn1.String]...
        %  ["fIn2"] [10000000] [fh.fIn2.String]...
        %  ["preset"] [10000000] [fh.preset.Value]...
        %  ["tmpRes"] [10000000] [fh.tmpRes.String]...
        %  ["spaRes"] [10000000] [fh.spaRes.String]...
        %  ["bdSpa"] [10000000] [fh.bdSpa.String]...
        %  ["registrateCorrect"] [10000000] [fh.registrateCorrect.Value]...
        %  ["bleachCorrect"] [10000000] [fh.bleachCorrect.Value]...
        %  ["smoXY"] [10000000] [fh.smoXY.String]...
        %  ["noiseEstimation"] [10000000] [fh.noiseEstimation.Value]...
        %  ["thrArScl"] [10000000] [fh.thrArScl.String]...
        %  ["minDur"] [10000000] [fh.minDur.String]...
        %  ["minSize"] [10000000] [fh.minSize.String]...
        %  ["maxSize"] [10000000] [fh.maxSize.String]...
        %  ["circularityThr"] [10000000] [fh.circularityThr.String]...
        %  ["needTemp"] [10000000] [fh.needTemp.Value]...
        %  ["sigThr"] [10000000] [fh.sigThr.String]...
        %  ["maxDelay"] [10000000] [fh.maxDelay.String]...
        %  ["needRefine"] [10000000] [fh.needRefine.Value]...
        %  ["needGrow"] [10000000] [fh.needGrow.Value]...
        %  ["needSpa"] [10000000] [fh.needSpa.Value]...
        %  ["cRise"] [10000000] [fh.cRise.String]...
        %  ["cDelay"] [10000000] [fh.cDelay.String]...
        %  ["gtwSmo"] [10000000] [fh.gtwSmo.String]...
        %  ["detectGlo"] [10000000] [fh.detectGlo.Value]...
        %  ["ignoreTau"] [10000000] [fh.ignoreTau.Value]}
        
        %  ["infile01name"] [10000000] [fh.fIn1.String(2)] 
        %  ["infile02dir"] [10000000] [fh.fIn2.String(1)] 
        %  ["infile02name"] [10000000] [fh.fIn2.String(2)]};
        
        %conn.send(Network.toStreamPayload("BEUISync",responseFhValueArray));
    catch error
        disp(convertStringsToChars(strcat(structToChar(error.stack), ": ", error.message)));
        Supervisor.instanceExceptionSQL(convertStringsToChars(strcat(structToChar(error.stack), ": ", error.message)));
    end
end