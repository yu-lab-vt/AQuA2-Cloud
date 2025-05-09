function [header] = writeBMP(IM)
    header = uint8([66;77;118;5;0;0;0;0;0;0;54;0;0;0;40;0;0;0;21;0;0;0;21;0;0;0;1;0;24;0;0;0;0;0;64;5;0;0;0;0;0;0;0;0;0;0;0;0;0;0;0;0;0;0]);
    IMr = IM(:,:,1);
    IMg = IM(:,:,2);
    IMb = IM(:,:,3);
    clear IM;
    IM(:,:,1)=IMb';
    IM(:,:,2)=IMg';
    IM(:,:,3)=IMr';
    IM(:,:,:)=IM(:,end:-1:1,:);
    [i,j,~]=size(IM);
    header(19:22) = typecast(int32(i),'uint8'); %width
    header(23:26) = typecast(int32(j),'uint8'); %height
    IM = permute(IM,[3,1,2]);
    IM = reshape(IM,[i*3,j]);
    W = double(i)*3;
    W = ceil(W/4)*4;
    IM(3*i+1:W,:)=0; %padd zeros
    IM = IM(:); %linear
    header(35:38) = typecast(uint32(length(IM)),'uint8'); %datasize
    header = [header;IM];
    header(3:6) = typecast(uint32(length(header)),'uint8'); %filesize
end