#include <stdio.h>
#include <stdlib.h>


unsigned int gcd(unsigned int pi, unsigned int pn){

    unsigned int j=0;
    for(j=2; j<pi; j++){
        if(pi%j==0 && pn%j==0){
            return j;
        }
    }
    return 1;
}

int main(void){

    unsigned int n=0, k=0, i=0;
    scanf("%u", &n);

    for(i=1; i<=n; i++){
        if(gcd(i, n)==1){
            k++;
        }
    }
    printf("phi(%u)=%u", n, k);

    return 0;
}
