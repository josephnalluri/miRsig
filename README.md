# miRsig
miRsig - A tool to determine significant miRNAs in a disease or among multiple diseases using an network inference based approach and graph matching methodology.


#Requirements:
1) The input expression matrix should be samples as rows and miRNAs/genes/features as columns, i.e. (samples x miRNAs). The names of all the miRNAs HAVE to be "G1", "G2", etc. 
2) No columns (genes/miRNAs/features) should have ALL zeros. Remove all such columns before processing.
