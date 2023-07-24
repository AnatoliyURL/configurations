import AddSetModal from "../../../modals/shipper/configurations/add-set-modal.js";

const API = {
  getContent: function (data) {
    return fetchRequest("/shipper/configurations/get-content", "POST", data);
  },

  getInfToSet: function (data) {
    return fetchRequest("/shipper/configurations/get-inf-set", "POST", data);
  },

  getInfToConfiguration: function (data) {
    return fetchRequest(
      "/shipper/configurations/get-inf-configuration",
      "POST",
      data
    );
  },

  addPositionToSet: function (data) {
    return fetchRequest(
      "/shipper/configurations/add-position-to-set",
      "POST",
      data
    );
  },

  deletePositionToSet: function (data) {
    return fetchRequest(
      "/shipper/configurations/delete-position-to-set",
      "POST",
      data
    );
  },

  changeFranchiseToSet: function (data) {
    return fetchRequest(
      "/shipper/configurations/change-franchise-to-set",
      "POST",
      data
    );
  },

  changeGroupRoleToSet: function (data) {
    return fetchRequest(
      "/shipper/configurations/change-group-role-to-set",
      "POST",
      data
    );
  },

  changeTitleToSet: function (data) {
    return fetchRequest(
      "/shipper/configurations/change-title-to-set",
      "POST",
      data
    );
  },

  deleteCategoriesToPoint: function (data) {
    return fetchRequest(
      "/shipper/configurations/delete-categories-to-point",
      "POST",
      data
    );
  },

  addCategoriesToPoint: function (data) {
    return fetchRequest(
      "/shipper/configurations/add-categories-to-point",
      "POST",
      data
    );
  },

  addPointsTodCategories: function (data) {
    return fetchRequest(
      "/shipper/configurations/add-points-to-categories",
      "POST",
      data
    );
  },

  createSet: function (data) {
    return fetchRequest("/shipper/configurations/create-set", "POST", data);
  },
};

window.addEventListener("load", function () {
  let content = document.getElementById("content");
  let contentSet = document.getElementById("content-set");
  let contentConfiguration = document.getElementById("content-configuration");
  let btnAddSet = document.getElementById("open-add-set-modal");

  let points = document.querySelector('[name="point_id[]"]');
  let configurations = document.querySelector('[name="conf_id[]"]');

  const setModal = document.getElementById("right-modal");
  const configurationModal = document.getElementById("left-modal");
  const setAddModal = new AddSetModal("add-set-modal");

  const applyFilterButton = this.document.getElementById("apply-filter");
  applyFilterButton.onclick = update;

  btnAddSet.addEventListener("click", function () {
    setAddModal.show();
  });

  setAddModal.onSubmit = function (event, data) {
    API.createSet(data).then((res) => {
      setAddModal.hide();
      update();
    });
  };

  const clearFilterButton = this.document.getElementById("clear-filter");
  clearFilterButton.addEventListener("click", function () {
    configurations.value = 1;
    configurations.dispatchEvent(new Event("chosen:updated"));
    points.value = "";
    points.dispatchEvent(new Event("chosen:updated"));
    update();
  });

  const titles = this.document.querySelectorAll(".title") || [];
  titles.forEach((title) => {
    title.addEventListener("click", function (e) {
      this.closest(".roles-block").classList.toggle("active");
    });
  });

  function update() {
    let data = {
      configurations: $(configurations).val(),
      points: $(points).val(),
    };

    API.getContent(data).then((res) => {
      content.innerHTML = res.content;
    });
  }

  function updateSet(id) {
    API.getInfToSet({ set_id: id }).then((res) => {
      contentSet.innerHTML = res.content;
      contentSet.querySelectorAll(".chosen").forEach((e) => $(e).chosen());
      const title = contentSet.querySelector('[name="title"]');
      const franchise = contentSet.querySelector('[name="franchise"]');
      const role = contentSet.querySelector('[name="role_group[]"]');
      $(franchise).on("change", function (e) {
        API.changeFranchiseToSet({ set_id: id, fid: franchise.value }).then(
          function () {
            updateSet(id);
          }
        );
      });

      $(role).on("change", function (e) {
        API.changeGroupRoleToSet({ set_id: id, groups_id: $(role).val() }).then(
          function () {
            updateSet(id);
          }
        );
      });

      title.addEventListener("focusout", function () {
        API.changeTitleToSet({ set_id: id, title: title.value }).then(
          function () {
            updateSet(id);
          }
        );
      });

      update();
    });
  }

  function updateConfiguration(point_id) {
    API.getInfToConfiguration({ point_id: point_id }).then((res) => {
      contentConfiguration.innerHTML = res.content;
      contentConfiguration
        .querySelectorAll(".chosen")
        .forEach((e) => $(e).chosen());
    });

    update();
  }

  this.document.addEventListener("click", function (e) {
    if (
      !setModal.contains(e.target) &&
      !e.target.classList.contains(e.target)
    ) {
      setModal.classList.remove("modal-show");
      contentSet.innerHTML = "";
    }

    if (
      !configurationModal.contains(e.target) &&
      !e.target.classList.contains(e.target)
    ) {
      configurationModal.classList.remove("modal-show");
      contentConfiguration.innerHTML = "";
    }

    if (e.target.classList.contains("delete-position")) {
      API.deletePositionToSet({
        set_id: e.target.dataset.setId,
        position_ids: e.target.dataset.positionId,
      }).then(() => {
        updateSet(e.target.dataset.setId);
      });
    }

    if (e.target.classList.contains("add-position")) {
      API.addPositionToSet({
        set_id: e.target.dataset.setId,
        positions_ids: $(setModal.querySelector('[name="positions[]"]')).val(),
      }).then((res) => {
        updateSet(e.target.dataset.setId);
      });
    }

    if (e.target.classList.contains("add-point")) {
      API.addPointsTodCategories({
        points: $(setModal.querySelector('[name="points[]"]')).val(),
        categories_id: e.target.dataset.setId,
      }).then((res) => {
        updateSet(e.target.dataset.setId);
      });
    }

    if (e.target.classList.contains("edit-set")) {
      setModal.classList.add("modal-show");
      updateSet(e.target.dataset.setId);
    }

    if (e.target.classList.contains("edit-configuration")) {
      configurationModal.classList.add("modal-show");
      updateConfiguration(e.target.dataset.pointId);
    }

    if (e.target.classList.contains("delete-categories")) {
      API.deleteCategoriesToPoint({
        point_id: e.target.dataset.pointId,
        categories_id: e.target.dataset.categoriesId,
      }).then(() => {
        updateConfiguration(e.target.dataset.pointId);
      });
    }

    if (e.target.classList.contains("delete-point")) {
      API.deleteCategoriesToPoint({
        point_id: e.target.dataset.pointId,
        categories_id: e.target.dataset.setId,
      }).then(() => {
        updateSet(e.target.dataset.setId);
      });
    }

    if (e.target.classList.contains("add-categories-to-point")) {
      API.addCategoriesToPoint({
        point_id: e.target.dataset.pointId,
        categories_id: $(
          configurationModal.querySelector('[name="categories[]"]')
        ).val(),
      }).then((res) => {
        updateConfiguration(e.target.dataset.pointId);
      });
    }
  });

  update();

  $(".chosen").chosen();
});
