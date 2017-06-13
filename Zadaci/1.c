#include <stdio.h>
#include <stdlib.h>

int main(void){

    int sum=0, i=0, j=0, k=0;
    scanf("%d", &k);

    for(i=1; i<=k; i++){
        sum=0;
        for(j=1; j<i; j++){
            if(i%j==0)
                sum=sum+j;
        }
        if(i<sum)
            printf("%d\n", i);
    }

    return 0;
}
