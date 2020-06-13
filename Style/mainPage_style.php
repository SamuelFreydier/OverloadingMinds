<style>
*{
    margin:0px;
    padding: 0px;
    font-family: 'Jost','Roboto', sans-serif;
}

body{
    background-color: #189BA3;
    height: 94vh;
}

#topMenu{
    display: flex;
    flex-direction: row;
    justify-content: space-between;
    align-items: center;
    overflow: hidden;
    background-color: #1F777C;
    position: fixed; 
    top: 0;
    height: 6vh;
    width: 100%; 
}

#topMenu a:first-of-type{
    height: 50px;
    margin-right: 0.5vw;
}

.profilPic{
    width: 50px;
    height: 50px;
    border-radius: 100%;
}

#topMenu #profilContainer{
    margin-left: 5vw;
    font-size: 1.5em;
    display: flex;
    flex-direction: row;
    align-items: center;
}

#topMenu #search_container{
    display: flex;
}

#topMenu #search_container button{
    padding: 6px 10px;
    margin-right: 2vw;
    background: #F9F8E6;
    font-size: 17px;
    border: none;
    cursor: pointer;
}

#topMenu #search_container form button{
    float: right;
    padding: 6px 10px;
    margin-right: 2vw;
    background: #F9F8E6;
    font-size: 17px;
    border: none;
    cursor: pointer;
}

#topMenu input[type=text] {
    padding: 6px;
    font-size: 17px;
    border: none;
}

content{
    display: flex;
    flex-direction: row;
    flex-wrap: nowrap;
    justify-content: space-between;
}

#timeline{
    width: 40vw;
    margin-top: 6vh;
}

#tweetBox{
    display: flex;
    flex-direction: row;
    background-color: #F9F8E6;
    margin-bottom: 2vh;
    padding: 10px 3vw 10px 10px;
}

#tweetPost{
    display: flex;
    flex-direction: column;
    margin-left: 20px;
    width: 100%;
}

#tweetPost textarea{
    font-size: 1.2em;
    padding: 10px;
    height: 10vh;
    resize: none;
}

#tweetPost button {
    margin: 5px;
    align-self: flex-end;
    font-size: 1.2em;
    padding: 0px 5px;
}

.tweetMainContainer{
    display: flex;
    flex-direction: row;
    align-items: center;
    margin-left: 72px;
    padding-top: 2vh;
}

.tweetMainContainer i{
    margin-right: 5px;
}

.tweetContainer{
    display: flex;
    flex-direction: row;
    font-size: 1.2em;
    padding: 9px 9px 15px 15px;
}

.tweetBody{
    display: flex;
    flex-direction: column;
    text-align: left;
    padding-left: 10px;
    width: 100%;
}

.tweetInfo{
    display: flex;
    flex-direction: row;
    margin-bottom: 3px;
}

.tweetInfo div:first-of-type{
    margin-right: 20px;
}

.tweetText{
    padding-right: 15px;
}

.tweerLikes{
    align-self: flex-end;
    display: flex;
    flex-direction: row;
    align-items: center;
}

.tweerLikes{
    align-self: flex-end;
    display: flex;
    flex-direction: row;
    align-items: center;
}

.tweerLikes button{
    border: none;
    text-decoration: none;
    background-color: #F9F8E6;
    margin-right: 3px;
    cursor: pointer;
}

.tweerLikes p{
    margin-right: 10px;
}

.sideBlocks{
    width: 30vw; 
    height: 100%;
}

#profilInfo{
    display: flex;
    flex-direction: column;
    padding: 18px;

    font-size: 1.4em;
    background-color: #F9F8E6;
    margin-bottom: 3vh;
}

#ProfilInfoPic {
    display: flex;
    flex-direction: row;
    justify-content: space-between;
    align-items: center;
}

#ProfilInfoPic button {
    padding: 0px 5px;
    font-size: 1em;
}

#profilInfoText{
    margin-left: 1vw;
    margin-top: 10px;
}

#profilInfoText p:first-of-type{
    margin-bottom: 10px;
}

.profilInfoStats{
    display: flex;
    flex-direction: row;
    font-size: 0.87em;
    margin-right: 1vw;
    margin-top: 5px;
}

.profilInfoStats p{
    margin-right: 5px;
}

.memberButton{
    padding: 0px 5px;
    font-size: 1em; 
    margin: auto;
}

.memberProfilInfo{
    display: flex;
    flex-direction: row;
    padding: 18px;
    font-size: 1.4em;
    background-color: #F9F8E6;
    margin-bottom: 3vh;
}



/**************** MEDIA QUERY *****************/



@media screen and (max-width: 1035px){
    .sideBlocks{ width: 10vw; }
    #timeline  { width: 60vw; } 
}

@media screen and (max-width: 786px){
    .sideBlocks{ width: 5vw;  }
    #timeline  { 
        width: 70vw;
        margin-top: 120px; 
    } 
    #topMenu{
        flex-direction: column;
        overflow: visible;
        height: auto;
    }
    #topMenu #profilContainer{
        margin-left: 0vw;
    }
    #topMenu #profilContainer img{
        display: none;
    }
    #topMenu a:first-of-type{
        height: 0px;
    }
    #topMenu #search_container button{

        margin-right: 0vw;
        margin-bottom: 5px;
    }
}

@media screen and (max-width: 573px){
    .sideBlocks{ width: 0vw; }
    #timeline  { width: 100vw; } 
}

@media screen and (max-width: 385px){
    .memberProfilInfo{
        flex-direction: column;
    }
}

</style>