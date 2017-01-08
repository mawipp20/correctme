<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\LessonForm;

/**
 * testCrudSearch represents the model behind the search form about `app\models\LessonForm`.
 */
class testCrudSearch extends LessonForm
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['startKey', 'typeTasks', 'typeMixing'], 'safe'],
            [['teacherId', 'numTasks', 'numStudents', 'numTeamsize', 'thinkingMinutes', 'earlyPairing', 'namedPairing'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = LessonForm::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'teacherId' => $this->teacherId,
            'numTasks' => $this->numTasks,
            'numStudents' => $this->numStudents,
            'numTeamsize' => $this->numTeamsize,
            'thinkingMinutes' => $this->thinkingMinutes,
            'earlyPairing' => $this->earlyPairing,
            'namedPairing' => $this->namedPairing,
        ]);

        $query->andFilterWhere(['like', 'startKey', $this->startKey])
            ->andFilterWhere(['like', 'typeTasks', $this->typeTasks])
            ->andFilterWhere(['like', 'typeMixing', $this->typeMixing]);

        return $dataProvider;
    }
}
