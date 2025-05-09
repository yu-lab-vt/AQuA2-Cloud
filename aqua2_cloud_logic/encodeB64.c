/*==========================================================
 * encodeB64.c - converts a byte vector to base64
 *
 * The calling syntax is:
 *
 *      [B] = encodeB64(B)
 *
 *      input:   - B     : vector of uint8
 *
 *      output:  - B     : vector of base64 char
 *
 * This is a MEX-file for MATLAB.
 *
 *========================================================*/

#include "mex.h" 

/* The computational routine */
void Convert(unsigned char *in, unsigned char *out,unsigned long Nin, unsigned long Nout)
{
    int temp; 
    static unsigned char alphabet[64] = {65,66,67,68,69,70,71,72,73,74,75,76,77,78,79,80,81,82,83,84,85,86,87,88,89,90,97,98,99,100,101,102,103,104,105,106,107,108,109,110,111,112,113,114,115,116,117,118,119,120,121,122,48,49,50,51,52,53,54,55,56,57,43,47};
    for (int i=0;i<(Nin-2);i+=3){
        temp = in[i+2] | (int)in[i+1]<<8 | (int)in[i]<<16;
        for (int j=0;j<4;j++){
            out[3+(i/3)*4-j] = alphabet[(temp >> (j*6)) & 0x3f];
        }
    }
    if (Nin%3==1){
        temp = (int)in[Nin-1]<<16;
        out[Nout-1] = 61;
        out[Nout-2] = 61;
        out[Nout-3] = alphabet[(temp >> 12) & 0x3f];
        out[Nout-4] = alphabet[(temp >> 18) & 0x3f];
    }
    if (Nin%3==2){
        temp = in[Nin-1]<<8 | (int)in[Nin-2]<<16;
        out[Nout-1] = 61;
        out[Nout-2] = alphabet[(temp >> 6) & 0x3f];
        out[Nout-3] = alphabet[(temp >> 12) & 0x3f];
        out[Nout-4] = alphabet[(temp >> 18) & 0x3f];
    }
}

/* The gateway function */
void mexFunction( int nlhs, mxArray *plhs[],int nrhs, const mxArray *prhs[])
{
    unsigned char *InputV;           /* input vector 1*/
    unsigned char *OutputV;          /* output vector 1*/
    unsigned long Nin;
    unsigned long Nout;
    
    /* check for proper number of arguments */
    if(nrhs!=1) {
        mexErrMsgIdAndTxt("MyToolbox:arrayProduct:nrhs","One inputs required.");
    }
    if(nlhs!=1) {
        mexErrMsgIdAndTxt("MyToolbox:arrayProduct:nlhs","One output required.");
    }
     /* make sure the first input argument is scalar integer*/
    if( !mxIsClass(prhs[0],"uint8") || mxGetNumberOfElements(prhs[0]) == 1 || mxGetN(prhs[0]) != 1)  {
        mexErrMsgIdAndTxt("MyToolbox:arrayProduct:notRowInteger","Input one must be uint8 column vector.");
    }
    
    /* get the value of the scalar input  */
    InputV = mxGetPr(prhs[0]);
    Nin = mxGetM(prhs[0]); /*number of input bytes */
    Nout = 4*((Nin+2)/3);
    
    /* create the output matrix */
    plhs[0] = mxCreateNumericMatrix((mwSize)Nout,1,mxUINT8_CLASS,mxREAL);
    
    /* get a pointer to the real data in the output matrix */
    OutputV = (unsigned char *) mxGetData(plhs[0]);
    
    /* call the computational routine */
    Convert(InputV,OutputV,Nin,Nout);
}