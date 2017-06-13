#include <stdio.h>
#include <stdlib.h>
#include <math.h>
int main(void){

    unsigned int n=1, m=1, i=1;
    scanf("%u", &n);

    for(m=n; m>=1; m--)
        for(i=1; i<=sqrt(m); i++)
            if(m%10==9 && m%3==1 && i*i==m){
                printf("Najveci broj s tim svojstvima (<=%u) je %u.", n, m);
                exit(-1);
            }

    printf("Ne postoji takav broj m.");

    return 0;
}
