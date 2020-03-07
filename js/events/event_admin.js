let data = [];
let companys = [];
let currentCompany = '';
const apiPath = './sup/event_admin_api.php';
const imgPath = './images/';

function callMsg(success, msg) {
  let alert = null;
  if (success) {
    alert = $('#msg-success');
  } else {
    alert = $('#msg-warning');
  }
  alert.html(msg).css('opacity', 100);
  setTimeout(() => {
    alert.css('opacity', 0);
  }, 2000);
}
function renderCompany() {
  let options = `
    <option value="">All</option>
  `;
  for (let el of companys) {
    options += `
      <option value="${el.id}">${el.username}</option>
    `;
  }
  $('#companys').html(options);
}
function renderTable(cid = currentCompany) {
  let info = []
  if(cid === '') {
    info = data
  } else {
    currentCompany = cid
    info = data.filter(el => el.cid === cid)
  }
  let table = '';
  for (let el of info) {
    table += `
      <tr>
        <td>
          <button class="delete btn btn-sm btn-outline-danger" data-id="${el.id}">delete</button>
        </td>
        <td>
          <img class="img-fluid" src="${imgPath + el.banner}" alt="">
        </td>
        <td>${el.title}</td>
        <td>${el.username}</td>
        <td>${el.date}</td>
        <td>${el.location}</td>
        <td class="table-content" data-id="${el.id}" data-toggle="modal" data-target="#contentModal">
          <div class="table-content">${el.content}</div>
        </td>
      </tr>
    `;
  }
  $('#tableBody').html(table);
}

const getData = function(cid = '', page = '') {
  return new Promise(function(resolve, reject) {
    $.ajax({
      url: apiPath + `?cid=${cid}&page=${page}`,
      type: 'GET',
      success: function(d) {
        console.log(d)
        let res = JSON.parse(d);
        if (res.success) {
          data = res.data.eventsData;
          companys = res.data.companys;
          renderTable();
          resolve();
        } else {
          reject();
        }
      }
    });
  });
};

$(document).ready(function() {
  getData().then(() => {
    renderTable();
    renderCompany();
  });
  // 顯示 content modal
  $(document).on('click', '[data-toggle=modal]', e => {
    let id = e.currentTarget.dataset.id;
    let info = data.find(el => el.id === id)
    $('#contentModal .modal-body').html(info.content)
  })
  // 切換顯示
  $(document).on('change', '#companys', e => {
    e.preventDefault();
    let cid = e.target.value;
    renderTable(cid)
  });
  // 刪除
  $(document).on('click', 'button.delete', e => {
    e.preventDefault();
    let id = e.target.dataset.id;
    $.ajax({
      url: apiPath,
      method: 'DELETE',
      data: `[${id}]`,
      success: function(d) {
        console.log(d)
        let res = JSON.parse(d);
        callMsg(res.success, res.msg);
        if (res.success) {
          getData().then(() => {
            renderTable();
          });
        }
      }
    });
  });
});
