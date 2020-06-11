<style>
*{
    margin:0px;
    padding: 0px;
    font-family: 'Jost','Roboto', sans-serif;
    text-align: center;
}

content{
    display: grid;
    grid-template-columns: 33.3vw 33.3vw 33.3vw;
    grid-template-rows: 33vh 34vh 33vh;
    background-color: #F9F8E6;
}

#Title{
    font-size: 4em;
    color: #189BA3;
    grid-column: 2;
    padding-bottom: 1vh;
    align-self: end;
}

#Image_TopRight{
    justify-self: end;
    height: 241px;
    width: 673px;
    grid-column-start: 3;
    grid-row-start: 1;
    background-image: url("../Ressources/BG_Login_TopRight.png");
    background-repeat: no-repeat;
}

/**/
#login_box{
    grid-column-start: 2;
    grid-row-start: 2;
    justify-self: center;
    display: flex;
    flex-direction: column;
    width: 370px;
    background-color: #FF8B8B;
    border-radius: 1px;
    padding-top: 3vh;
}

form > div{
    margin-top: 5%;
    height: 6vh;
}

input{
    background-color: #F9F8E6;
    color: #189BA3;
    font-size: 1.4em;
    border-style: none;
    border-radius: 3px;
    width: 75%;
    height: 70%;
    text-align: start;
    padding: 6px 0px 6px  1vw;
}

input::placeholder{
    color: #189BA3;
}

form > a{
    color: #F9F8E6;
    font-size: 1.1em;
}
/*
form > a:first-of-type{
    align-self: flex-end;
    margin-right: 5vh;
}
*/
button{
    border-style: none;
    border-radius: 3px;
    width: 79%;
    height: 15%;
    margin:  auto;
    font-size: 1.4em;
    color: #F9F8E6;
    background-color: #189BA3;
}
/**/

#Image_BottomLeft{
    min-height: 460px;
    min-width: 484px;
    grid-column: 1;
    grid-row: 2 / 4;
    background-position: bottom;
    background-image: url("../Ressources/BG_Login_BottomLeft.png");
    background-repeat: no-repeat;
    margin-right: auto;
}



/***************** MEDIA QUERY *****************/



@media screen and (max-width: 1327px), screen and (max-height: 805px) {
    #Image_TopRight{
        background-size: 336px 120px;
        height: 120px;
        width: 336px;
    }
    #Title{
        font-size: 3em;
    }
    #Image_BottomLeft{
        background-size: 242px 230px;
        min-height: 230px;
        min-width: 242px;
    }
}

@media screen and (max-width: 975px){
    #Title{
        font-size: 2em;
    }
}

@media screen and (max-width: 647px){
    #Title{
        font-size: 1em;
    }
}

@media screen and (max-width: 647px), screen and (max-height: 609px){
    #Image_TopRight{
        background-size: 269px 96px;
        height: 96px;
        width: 269px;
    }
    #Image_BottomLeft{
        background-size: 121px 115px;
        min-height: 115px;
        min-width: 121px;
    }

    input{
        font-size: 1.2em;
    }

    button{
        font-size: 1.2em;
    }

    #login_box{
        width: 300px;
    }
}

</style>