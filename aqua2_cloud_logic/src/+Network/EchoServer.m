classdef EchoServer < WebSocketServer
    %ECHOSERVER Summary of this class goes here
    %   Detailed explanation goes here
    
    properties
    end
    
    methods
        function obj = EchoServer(varargin)
            %Constructor
            obj@WebSocketServer(varargin{:});
        end
    end
    
    methods (Access = protected)
        function onOpen(obj,conn,message)
            fprintf('Data message from AQuA2-Cloud instance')
        end
        
        function onTextMessage(obj,conn,message)
            % This function sends an echo back to the client
            %fprintf('Echo server onTextMessage: %s\n',message)
            conn.send(message); % Echo
        end
        
        function onBinaryMessage(obj,conn,bytearray)
            % This function sends an echo back to the client
            Network.BEFunctionRoutingTable(obj,conn,bytearray); % Echo
        end
        
        function onError(obj,conn,message)
            return
            %fprintf('Echo server onError: %s\n',message)
        end
        
        function onClose(obj,conn,message)
            return
            %fprintf('Echo server onClose: %s\n',message)
        end
    end
end

