PGDMP                      }           postgres    17.4    17.4 ,    V           0    0    ENCODING    ENCODING        SET client_encoding = 'UTF8';
                           false            W           0    0 
   STDSTRINGS 
   STDSTRINGS     (   SET standard_conforming_strings = 'on';
                           false            X           0    0 
   SEARCHPATH 
   SEARCHPATH     8   SELECT pg_catalog.set_config('search_path', '', false);
                           false            Y           1262    5    postgres    DATABASE     n   CREATE DATABASE postgres WITH TEMPLATE = template0 ENCODING = 'UTF8' LOCALE_PROVIDER = libc LOCALE = 'it-IT';
    DROP DATABASE postgres;
                     postgres    false            Z           0    0    DATABASE postgres    COMMENT     N   COMMENT ON DATABASE postgres IS 'default administrative connection database';
                        postgres    false    4953            �            1259    16787 
   challenges    TABLE     �   CREATE TABLE public.challenges (
    name text NOT NULL,
    description text,
    date_start timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    date_end timestamp without time zone,
    winners text[],
    image text
);
    DROP TABLE public.challenges;
       public         heap r       postgres    false            �            1259    16793    comments    TABLE     �   CREATE TABLE public.comments (
    id integer NOT NULL,
    user_id character(1),
    post_id integer,
    content text NOT NULL,
    created_at timestamp without time zone DEFAULT now()
);
    DROP TABLE public.comments;
       public         heap r       postgres    false            �            1259    16799    comments_id_seq    SEQUENCE     �   CREATE SEQUENCE public.comments_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 &   DROP SEQUENCE public.comments_id_seq;
       public               postgres    false    218            [           0    0    comments_id_seq    SEQUENCE OWNED BY     C   ALTER SEQUENCE public.comments_id_seq OWNED BY public.comments.id;
          public               postgres    false    219            �            1259    16800    drafts    TABLE     ,  CREATE TABLE public.drafts (
    id integer NOT NULL,
    creator text,
    created_at timestamp without time zone DEFAULT now() NOT NULL,
    type character varying(32),
    description text,
    tags text[],
    file_location character varying(255),
    variation_of text,
    challenge_of text
);
    DROP TABLE public.drafts;
       public         heap r       postgres    false            �            1259    16806    drafts_id_seq    SEQUENCE     �   CREATE SEQUENCE public.drafts_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 $   DROP SEQUENCE public.drafts_id_seq;
       public               postgres    false    220            \           0    0    drafts_id_seq    SEQUENCE OWNED BY     ?   ALTER SEQUENCE public.drafts_id_seq OWNED BY public.drafts.id;
          public               postgres    false    221            �            1259    16807    snips    TABLE     "  CREATE TABLE public.snips (
    id integer NOT NULL,
    creator text,
    views integer DEFAULT 0,
    created_at timestamp without time zone DEFAULT now(),
    description text,
    element_type character varying(32),
    tags text[],
    file_location character varying(255) NOT NULL,
    likes integer DEFAULT 0,
    saved integer DEFAULT 0,
    variation_of text,
    challenge_of text,
    challenge_points integer,
    CONSTRAINT check_challenge_points_in_challenge_of CHECK (((challenge_of IS NOT NULL) OR (challenge_points IS NULL)))
);
    DROP TABLE public.snips;
       public         heap r       postgres    false            �            1259    16817    snips_id_seq    SEQUENCE     �   CREATE SEQUENCE public.snips_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 #   DROP SEQUENCE public.snips_id_seq;
       public               postgres    false    222            ]           0    0    snips_id_seq    SEQUENCE OWNED BY     =   ALTER SEQUENCE public.snips_id_seq OWNED BY public.snips.id;
          public               postgres    false    223            �            1259    16818    users    TABLE     M  CREATE TABLE public.users (
    id integer NOT NULL,
    username character varying(50) NOT NULL,
    email character varying(100) NOT NULL,
    password character varying(255) NOT NULL,
    likedsnippets text[],
    savedsnippets text[],
    bio text DEFAULT 'This is my bio :)'::text,
    followers text[],
    following text[]
);
    DROP TABLE public.users;
       public         heap r       postgres    false            �            1259    16824    users_id_seq    SEQUENCE     �   CREATE SEQUENCE public.users_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 #   DROP SEQUENCE public.users_id_seq;
       public               postgres    false    224            ^           0    0    users_id_seq    SEQUENCE OWNED BY     =   ALTER SEQUENCE public.users_id_seq OWNED BY public.users.id;
          public               postgres    false    225            �           2604    16825    comments id    DEFAULT     j   ALTER TABLE ONLY public.comments ALTER COLUMN id SET DEFAULT nextval('public.comments_id_seq'::regclass);
 :   ALTER TABLE public.comments ALTER COLUMN id DROP DEFAULT;
       public               postgres    false    219    218            �           2604    16826 	   drafts id    DEFAULT     f   ALTER TABLE ONLY public.drafts ALTER COLUMN id SET DEFAULT nextval('public.drafts_id_seq'::regclass);
 8   ALTER TABLE public.drafts ALTER COLUMN id DROP DEFAULT;
       public               postgres    false    221    220            �           2604    16827    snips id    DEFAULT     d   ALTER TABLE ONLY public.snips ALTER COLUMN id SET DEFAULT nextval('public.snips_id_seq'::regclass);
 7   ALTER TABLE public.snips ALTER COLUMN id DROP DEFAULT;
       public               postgres    false    223    222            �           2604    16828    users id    DEFAULT     d   ALTER TABLE ONLY public.users ALTER COLUMN id SET DEFAULT nextval('public.users_id_seq'::regclass);
 7   ALTER TABLE public.users ALTER COLUMN id DROP DEFAULT;
       public               postgres    false    225    224            K          0    16787 
   challenges 
   TABLE DATA           ]   COPY public.challenges (name, description, date_start, date_end, winners, image) FROM stdin;
    public               postgres    false    217   �4       L          0    16793    comments 
   TABLE DATA           M   COPY public.comments (id, user_id, post_id, content, created_at) FROM stdin;
    public               postgres    false    218   �5       N          0    16800    drafts 
   TABLE DATA           }   COPY public.drafts (id, creator, created_at, type, description, tags, file_location, variation_of, challenge_of) FROM stdin;
    public               postgres    false    220   �5       P          0    16807    snips 
   TABLE DATA           �   COPY public.snips (id, creator, views, created_at, description, element_type, tags, file_location, likes, saved, variation_of, challenge_of, challenge_points) FROM stdin;
    public               postgres    false    222   �6       R          0    16818    users 
   TABLE DATA           w   COPY public.users (id, username, email, password, likedsnippets, savedsnippets, bio, followers, following) FROM stdin;
    public               postgres    false    224   �9       _           0    0    comments_id_seq    SEQUENCE SET     >   SELECT pg_catalog.setval('public.comments_id_seq', 1, false);
          public               postgres    false    219            `           0    0    drafts_id_seq    SEQUENCE SET     <   SELECT pg_catalog.setval('public.drafts_id_seq', 19, true);
          public               postgres    false    221            a           0    0    snips_id_seq    SEQUENCE SET     ;   SELECT pg_catalog.setval('public.snips_id_seq', 44, true);
          public               postgres    false    223            b           0    0    users_id_seq    SEQUENCE SET     :   SELECT pg_catalog.setval('public.users_id_seq', 4, true);
          public               postgres    false    225            �           2606    16830    challenges challenges_pkey 
   CONSTRAINT     Z   ALTER TABLE ONLY public.challenges
    ADD CONSTRAINT challenges_pkey PRIMARY KEY (name);
 D   ALTER TABLE ONLY public.challenges DROP CONSTRAINT challenges_pkey;
       public                 postgres    false    217            �           2606    16832    comments comments_pkey 
   CONSTRAINT     T   ALTER TABLE ONLY public.comments
    ADD CONSTRAINT comments_pkey PRIMARY KEY (id);
 @   ALTER TABLE ONLY public.comments DROP CONSTRAINT comments_pkey;
       public                 postgres    false    218            �           2606    16834    drafts drafts_pkey 
   CONSTRAINT     P   ALTER TABLE ONLY public.drafts
    ADD CONSTRAINT drafts_pkey PRIMARY KEY (id);
 <   ALTER TABLE ONLY public.drafts DROP CONSTRAINT drafts_pkey;
       public                 postgres    false    220            �           2606    16836    snips snips_pkey 
   CONSTRAINT     N   ALTER TABLE ONLY public.snips
    ADD CONSTRAINT snips_pkey PRIMARY KEY (id);
 :   ALTER TABLE ONLY public.snips DROP CONSTRAINT snips_pkey;
       public                 postgres    false    222            �           2606    16838    users users_email_key 
   CONSTRAINT     Q   ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_email_key UNIQUE (email);
 ?   ALTER TABLE ONLY public.users DROP CONSTRAINT users_email_key;
       public                 postgres    false    224            �           2606    16840    users users_pkey 
   CONSTRAINT     N   ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_pkey PRIMARY KEY (id);
 :   ALTER TABLE ONLY public.users DROP CONSTRAINT users_pkey;
       public                 postgres    false    224            �           2606    16842    users users_username_key 
   CONSTRAINT     W   ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_username_key UNIQUE (username);
 B   ALTER TABLE ONLY public.users DROP CONSTRAINT users_username_key;
       public                 postgres    false    224            �           2606    16843    comments comments_post_id_fkey    FK CONSTRAINT     }   ALTER TABLE ONLY public.comments
    ADD CONSTRAINT comments_post_id_fkey FOREIGN KEY (post_id) REFERENCES public.snips(id);
 H   ALTER TABLE ONLY public.comments DROP CONSTRAINT comments_post_id_fkey;
       public               postgres    false    4781    222    218            �           2606    16848    comments comments_user_id_fkey    FK CONSTRAINT     �   ALTER TABLE ONLY public.comments
    ADD CONSTRAINT comments_user_id_fkey FOREIGN KEY (user_id) REFERENCES public.users(username);
 H   ALTER TABLE ONLY public.comments DROP CONSTRAINT comments_user_id_fkey;
       public               postgres    false    218    224    4787            �           2606    16853    drafts drafts_challange_of_fkey    FK CONSTRAINT     �   ALTER TABLE ONLY public.drafts
    ADD CONSTRAINT drafts_challange_of_fkey FOREIGN KEY (challenge_of) REFERENCES public.challenges(name);
 I   ALTER TABLE ONLY public.drafts DROP CONSTRAINT drafts_challange_of_fkey;
       public               postgres    false    4775    220    217            �           2606    16858    drafts drafts_creator_fkey    FK CONSTRAINT        ALTER TABLE ONLY public.drafts
    ADD CONSTRAINT drafts_creator_fkey FOREIGN KEY (creator) REFERENCES public.users(username);
 D   ALTER TABLE ONLY public.drafts DROP CONSTRAINT drafts_creator_fkey;
       public               postgres    false    224    4787    220            �           2606    16863    snips snips_creator_fkey    FK CONSTRAINT     �   ALTER TABLE ONLY public.snips
    ADD CONSTRAINT snips_creator_fkey FOREIGN KEY (creator) REFERENCES public.users(username) ON UPDATE CASCADE;
 B   ALTER TABLE ONLY public.snips DROP CONSTRAINT snips_creator_fkey;
       public               postgres    false    222    4787    224            �           2606    16868    snips vincolo_challenge    FK CONSTRAINT     �   ALTER TABLE ONLY public.snips
    ADD CONSTRAINT vincolo_challenge FOREIGN KEY (challenge_of) REFERENCES public.challenges(name);
 A   ALTER TABLE ONLY public.snips DROP CONSTRAINT vincolo_challenge;
       public               postgres    false    222    217    4775            K     x��ӱn�0���� LBیE�"�#Re�ɸ�GZ���Z�� ��ý�=�c.��Kx��D��4drB�
�@8�t�[��%�C�r��c)ӊ�$�J�E�vC�U�:�yl�x;�{?<x�{p}>�`��sQr��1H&Id��gV�D%Б����Z�Q�bsn��*�O{�'X��R�y��Kէ+/g�n��e�,�����u�_���˚Y躗6n��8֥�����fB��9��c���:�-��)n�����m��G�      L      x������ � �      N   �   x���;�0E�Ud0I��0�4A�@�$�w�Vft���C#�b��<x�b�SBS��I��NFk�ñig����^ܰ�����Fg[5ɡ;%!���.B���h��ɪ�q�KLc&S^*[��:��%y�h��f$�k~�D��B9wl��ι�<��>B���Z�      P   7  x��T]o�6}���h$�-�l+,�1�
�DKL$R�(�Z���+Yv�vAaZ��H�s�=�n���,b���Կe.fn�ŉ9Q�A�L#�/Ye:�s�e�j�2�jae���Z��:��C���9H򫬤���'�C1���!S;�(|�|>��GW"�<G����A�QfL�+#r٢uz�s�ɝj�J�+�	=�u7�y��p�� ��fqBc'�|�eŒ�%�y����� ʶ5uc��tA�E6���J�e���617>���y�U��N�F@�6��-�e�gp�,\5��F��{ �FWJKtZ�^v�L��,m��C)kIvV�5Մ�����C���� X��Ҁc%~�x�a�u#�J�a���0����^az<��.����<�	r� |	/�ǔ��b��Ů���HS��R�*���~�L�D�gG�U6�Y��2L�mV*�Y���[u�>y��~p����wb{a�:k�X���V�;������ ��Ĳ3ض�iΔ��A>�}���1Ά��T3�ߎ���s3tr���\Ƀ��Ύ���ܕ����0X��	�N�iĂ�U����X�L���gԻ1����OX�W��������C?���ֆ��J͵:#�oS��M�s�S������˫u�[��9�W2�iB����D�u�:*��޴:H�y���ͻw�_o߿�P[V�e5ي� H3Ǿs�-�ص{��̪�}��o�,�b싱a�cQ 5��^>	8�l�z��Nh9�b�@�����E�܉�6����8�pq�č� ��f�	��y������g���� ��      R   ~  x���Qo�@���W���&d�Q7�OE׵UAZZY���̌�(��Wݤ1�i6��9�y��9���hE�Bp�fG)薊H`,��h�`J6m�h?鐭��pP��M��U�'��!��pd�Oc7���FX�ޅT�T���x��SZ�NÚVDE����=�
�5�Q�N4W$�-���/~�t��u:!_lt�AOU$f� ���=����>C"�O��w<�t3ʯQ�-��e���=�i����x��Ɗ��o��ۗA��y6�̍�(%���uS9��2o���Ju��ƑV��K�]�]�䳩����$���
a�L��~���妦l�\�Q��Y-c�%HJ��)��4^-ɩ�����R��s�����KqG��������     