function AcknowldgeCommand(obj,conn,message)
    empty = {["AcknComd"] [10000000] [0]};
    conn.send(Network.toStreamPayload("AcknComd",empty));
end