let inputCont = document.getElementById("fileproduct");
let previewCont = document.querySelector(".image--slider");
let currentIndex = 0;
let imageSrc = [];
//let hiddenProductId = document.getElementById("lblproductId");

//console.log(hiddenProductId.value);

inputCont.addEventListener("change", (e) => {
  let filesArray = e.target.files;
  for (let i = 0; i < filesArray.length; i++) {
    const file = filesArray[i];
    if (file) {
      const fileType = filesArray[i].type;
      if (!fileType.includes("image/jp")) {
        alert("please select an jpeg image");
      } else {
        var newImage;
        const reader = new FileReader();
        reader.addEventListener("load", (e) => {
          newImage = `<div class="upload--image" ><div data-no="${currentIndex}" class="image--del"><img src="../../assets/icons/close.svg" /></div><img src="${e.target.result}" alt="Alternate Text" /></div>`;
          imageSrc[currentIndex] = newImage;
          productObject.images.push(e.target.result);
          currentIndex++;
          loadImages();
        });
        reader.readAsDataURL(file);
      }
    } else {
    }
  }
});
let ImageBytes = [];
let array_store = document.getElementById("HiddenField1");

const loadImages = () => {
  previewCont.innerHTML = "";
  for (var im in imageSrc) {
    previewCont.innerHTML += imageSrc[im];
  }
  ImageBytes = [];
  previewCont.childNodes.forEach((e) => {
    let splitedText = e.childNodes[1].src.split(",");
    let imageBasetext = splitedText[1];
    ImageBytes.push(imageBasetext);
  });
  array_store.value = ImageBytes.join(",");
  addDeleteButton();
};

const addDeleteButton = () => {
  let deleteButtons = document.querySelectorAll(".image--del");
  deleteButtons.forEach((del) => {
    del.addEventListener("click", (e) => {
      delete imageSrc[del.attributes[0].value];
      loadImages();
    });
  });
};
