module.exports = {
  up: (queryInterface, Sequelize) => {

    return queryInterface.bulkInsert('sam_vote', [
      {
        scenario_id: 1234,
        meeting_id: 5678,
        cap_cost: 1.1,
        om_cost: 2.2,
        lc_cost: 3.3,
        growth_comp: 4.4,
        jobs: 5.5,
        var_perf: 6.6,
        flood_ratio: 7.7,
        pvla: 8.8,
        years: 9.9
      } 
    ])   
  },

  down: (queryInterface, Sequelize) => {

    return queryInterface.bulkDelete('sam_vote', null, {});
  }
};
