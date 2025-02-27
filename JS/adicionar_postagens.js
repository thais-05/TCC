// Função para exibir um popup
function showPopup(popupId) {
    const popup = document.getElementById(popupId);
    if (popup) {
      popup.style.display = 'flex';
    }
  }
  
  // Função para fechar um popup
  function closePopup(popupId) {
    const popup = document.getElementById(popupId);
    if (popup) {
      popup.style.display = 'none';
    }
  }
  
  // Botão "Publicar"
  document.getElementById('publishBtn').addEventListener('click', function () {
    showPopup('popup'); // Exibe o popup de sucesso
  });
  
  // Botão "Fechar" do popup de sucesso
  document.getElementById('closePopup').addEventListener('click', function () {
    closePopup('popup'); // Fecha o popup de sucesso
  });
  
  // Botão "Cancelar"
  document.getElementById('cancelBtn').addEventListener('click', function () {
    showPopup('popupCancel'); // Exibe o popup de cancelamento
  });
  
  // Botão "Fechar" do popup de cancelamento
  document.getElementById('closePopupCancel').addEventListener('click', function () {
    closePopup('popupCancel'); // Fecha o popup de cancelamento
  });
  